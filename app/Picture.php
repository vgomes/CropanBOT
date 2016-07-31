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
    protected $appends = ['numVotes'];

    // Attributes
    public function getNumVotesAttribute()
    {
        return ($this->yes + $this->no);
    }

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
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Events
    static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        parent::creating(function (Picture $picture) {
            if (filter_var($picture->url, FILTER_VALIDATE_URL) === false) {
                // invalid url
                \Telegram::sendMessage([
                    'chat_id' => $picture->user_id,
                    'text' => "*$picture->url* no es una imagen válida",
                    'parse_mode' => 'Markdown'
                ]);

                return false;
            } else {
                try {
                    $hash = hashPicture($picture->url);
                    $picture->hash = $hash;

                    $hasher = new ImageHash();

                    $similar_pictures = Picture::whereNotNull('hash')->get()->filter(function (Picture $picture) use (
                        $hash,
                        $hasher
                    ) {
                        return ($hasher->distance($hash, $picture->hash) < 4);
                    });

                    if ($similar_pictures->count() > 0) {
                        \Telegram::sendPhoto([
                            'chat_id' => $picture->user_id,
                            'photo' => $picture->url,
                            'caption' => 'Esta imagen ya ha sido enviada antes, puede que por otro usuario'
                        ]);

                        return false;
                    }

                    return true;

                } catch (\Exception $e) {
                    \Telegram::sendMessage([
                        'chat_id' => $picture->user_id,
                        'text' => "*$picture->url* no es una imagen válida",
                        'parse_mode' => 'Markdown'
                    ]);

                    return false;
                }
            }
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
    }

    // Functions
    public function uploadToTumblr()
    {
        if (\App::environment('production')) {
            if (is_null($this->published_at)) {
                try {
                    $this->published_at = Carbon::now();
                    $this->save();

                    // publish to Tumblr
                    $client = new Client(env('TUMBLR_CONSUMER_KEY'), env('TUMBLR_CONSUMER_SECRET'));
                    $client->setToken(env('TUMBLR_TOKEN'), env('TUMBLR_TOKEN_SECRET'));
                    $client->createPost(env('TUMBLR_BLOG'), [
                        'type' => 'photo',
                        'state' => 'queue',
                        'tags' => env('TUMBLR_TAGS'),
                        'source' => $this->url
                    ]);
                } catch (RequestException $e) {
                    \Log::alert("Problem uploading: " . $this->url);
                }

                Diary::experienceFromImageGoingToTumblr($this);
            }
        }
    }

    public function sendToGroup()
    {
        $a = [
            "text" => "YLD",
            "url" => env('APP_URL') . "/v/$this->id/yld"
        ];
        $b = [
            "text" => "NO",
            "url" => env('APP_URL') . "/v/$this->id/no"
        ];
        $options = [[$a, $b]];

        $keyboard = ["inline_keyboard" => $options];

        try {
            $this->sent_at = Carbon::now();
            $this->save();

            \Telegram::sendPhoto([
                'chat_id' => env('TELEGRAM_GROUP_ID'),
                'photo' => $this->url,
                'reply_markup' => json_encode($keyboard)
            ]);

            Diary::experienceFromImageGettingPublished($this);
        } catch (TelegramSDKException $e) {
        }
    }
}
