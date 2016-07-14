<?php

namespace Cropan;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
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
            if (is_null($picture->user_id)) {
                // assign Cropanbot as uploader
                $bot_id = explode(':', env('TELEGRAM_BOT_TOKEN'));
                $bot_id = $bot_id[0];
                $picture->user_id = $bot_id;
            }

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
    }

    // Functions
    public function uploadToTumblr()
    {
        try {
            // publish to Tumblr
            $client = new Client(env('TUMBLR_CONSUMER_KEY'), env('TUMBLR_CONSUMER_SECRET'));
            $client->setToken(env('TUMBLR_TOKEN'), env('TUMBLR_TOKEN_SECRET'));
            $client->createPost(env('TUMBLR_BLOG'), [
                'type' => 'photo',
                'state' => 'queue',
                'tags' => env('TUMBLR_TAGS'),
                'source' => $this->url
            ]);

            $this->published_at = Carbon::now();
            $this->save();
        } catch (RequestException $e) {
            \Log::alert("Problem uploading: " . $this->url);
        }

        Diary::experienceFromImageGoingToTumblr($this);
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

        \Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_GROUP_ID'),
            'text' => $this->url,
            'reply_markup' => json_encode($keyboard)
        ]);

        Diary::experienceFromImageGettingPublished($this);
    }
}
