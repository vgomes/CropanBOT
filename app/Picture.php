<?php

namespace Cropan;

use Illuminate\Database\Eloquent\Model;
use Tumblr\API\Client;

class Picture extends Model
{
    protected $table = 'pictures';
    protected $fillable = ['update_id', 'url', 'user_id', 'sent_at', 'published_at'];

    // Relationships
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function uploadToTumblr() {
        // publish to tumblr
        $client = new Client(env('TUMBLR_CONSUMER_KEY'), env('TUMBLR_CONSUMER_SECRET'));
        $client->setToken(env('TUMBLR_TOKEN'), env('TUMBLR_TOKEN_SECRET'));
        $client->createPost(env('TUMBLR_BLOG'), [
            'type' => 'photo',
            'state' => 'queue',
            'tags' => env('TUMBLR_TAGS'),
            'source' => $this->url
        ]);
    }

    public function sendToGroup()
    {
        $keyboard = [["YLD", "NO"]];

        $markup = \Telegram::replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'one_time_keyboard' => true,
            'resize_keyboard' => true
        ]);

        \Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_GROUP_ID'),
            'text' => $this->url,
            'reply_markup' => $markup
        ]);
    }
}
