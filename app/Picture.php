<?php

namespace Cropan;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\ImageHash\ImageHash;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Tumblr\API\Client;
use Tumblr\API\RequestException;

class Picture extends Model
{
    protected $table = 'pictures';
    protected $fillable = ['update_id', 'url', 'user_id', 'sent_at', 'published_at', 'created_at', 'updated_at'];

    // Scopes
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    public function scopeQueue($query)
    {
        return $query->whereNull('sent_at');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeYes($query)
    {
        return $query->where('score', '>', 0)->where('yes', '>', 1);
    }

    public function scopeNo($query)
    {
        return $query->where('score', '<', 0)->where('no', '>', 0);
    }

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'telegram_id');
    }

    public function people()
    {
        return $this->belongsToMany(Person::class, 'people_pictures');
    }

    // Events
    static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        parent::creating(function (Picture $picture) {
            // check image is a valid image file, remove ?params
            if (!$picture->validImgUrl()) {
                // invalid url
                \Telegram::sendMessage([
                    'chat_id'    => $picture->user_id,
                    'text'       => "*$picture->url* no es una imagen válida",
                    'parse_mode' => 'Markdown'
                ]);

                return false;
            }

            // extract url from text
            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $picture->url, $match);
            $picture->url = $match[0][0];

            if (str_contains($picture->url, ['instagr.am', 'instagram.com'])) {
                $url = strtok($picture->url, '?'); // remove ?taken-by=_username_ from url
                $url = getPictureUrlFromInstagram($url); // replace instagram link by direct link to image

                if (is_null($url)) {
                    // invalid url
                    \Telegram::sendMessage([
                        'chat_id'    => $picture->user_id,
                        'text'       => "*$picture->url* no es una imagen válida",
                        'parse_mode' => 'Markdown'
                    ]);

                    return false;
                } else {
                    $picture->url = $url;
                }
            }

            // check url is not stored
            if (self::where('url', $picture->url)->exists()) {
                \Telegram::sendPhoto([
                    'chat_id' => $picture->user_id,
                    'photo'   => $picture->url,
                    'caption' => 'Esta imagen ya ha sido enviada antes, puede que por otro usuario'
                ]);

                return false;
            }

            // check by hash
            $hash = self::createHash($picture->url);
            $picture->hash = $hash;

            $similar_pictures = self::whereNotNull('hash')
                ->get()
                ->filter(function (Picture $picture) use ($hash) {
                    $hasher = new ImageHash();
                    return ($hasher->distance($hash, $picture->hash) < 4);
                });

            if ($similar_pictures->count() > 0) {
                \Telegram::sendPhoto([
                    'chat_id' => $picture->user_id,
                    'photo'   => $picture->url,
                    'caption' => 'Esta imagen ya ha sido enviada antes, puede que por otro usuario'
                ]);

                return false;
            }

            $picture->url = uploadToImgur($picture->url);

            return true;
        });

        // Exp for submitting images
        parent::created(function (Picture $picture) {
            Diary::experienceFromSubmittingPicture($picture);
        });

        parent::saving(function (Picture $picture) {
            $picture->score = ($picture->yes - $picture->no);

            return true;
        });

        parent::updated(function (Picture $picture) {
            if (is_null($picture->published_at)) {
                if ($picture->yes >= 4) {
                    $picture->uploadToTumblr();
                }
            }

            if ($picture->score == User::all()->count()) {
                Diary::experienceFromPerfectImage($picture);
            }

            if ($picture->score == (User::all()->count() * -1)) {
                Diary::experienceFromDisgraceImage($picture);
            }
        });

        parent::deleting(function (Picture $picture) {
            // delete votes
            Vote::wherePictureId($picture->id)->get()->each(function (Vote $vote) use ($picture) {
                \Log::info("Deleting vote for image $picture->id: $picture->url");
                $vote->delete();
            });

            // delete people_pictures entries
            \DB::table('people_pictures')->where('picture_id', '=', $picture->id)->delete();

            return true;
        });
    }

    // Functions
    public function uploadToTumblr()
    {
        if (\App::environment() == 'production') {
            if (is_null($this->published_at)) {
                try {
                    $this->published_at = Carbon::now();
                    $this->save();

                    // publish to Tumblr
                    $client = new Client(env('TUMBLR_CONSUMER_KEY'), env('TUMBLR_CONSUMER_SECRET'));
                    $client->setToken(env('TUMBLR_TOKEN'), env('TUMBLR_TOKEN_SECRET'));
                    $client->createPost(env('TUMBLR_BLOG'), [
                        'type'   => 'photo',
                        'state'  => 'queue',
                        'tags'   => env('TUMBLR_TAGS'),
                        'source' => $this->url
                    ]);
                } catch (RequestException $e) {
                    \Log::alert("Problem uploading: " . $this->url);
                }
            }
        }
    }

    public function sendToGroup()
    {
        $a = [
            "text" => "YLD",
            "url"  => env('APP_URL') . "/v/$this->id/yld"
        ];
        $b = [
            "text" => "NO",
            "url"  => env('APP_URL') . "/v/$this->id/no"
        ];
        $options = [[$a, $b]];

        $keyboard = ["inline_keyboard" => $options];

        try {
            if (ends_with($this->url, '.gif')) {
                \Telegram::sendDocument([
                    'chat_id'      => env('TELEGRAM_GROUP_ID'),
                    'document'     => $this->url,
                    'reply_markup' => json_encode($keyboard)
                ]);
            } else {
                \Telegram::sendPhoto([
                    'chat_id'      => env('TELEGRAM_GROUP_ID'),
                    'photo'        => $this->url,
                    'reply_markup' => json_encode($keyboard)
                ]);
            }

            $this->sent_at = Carbon::now();
            $this->save();
        } catch (TelegramSDKException $e) {
            $this->sent_at = null;
            $this->save();

            \Log::alert("Problem with $this->url | " . $e->getMessage());
        }
    }

    protected function validImgUrl()
    {
        return filter_var($this->url, FILTER_VALIDATE_URL);
    }

    protected static function createHash($url)
    {
        $hasher = new ImageHash;
        $hash = $hasher->hash($url);

        return $hash;
    }
}
