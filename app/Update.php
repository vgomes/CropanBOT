<?php

namespace Cropan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use \Telegram\Bot\Objects\Update as UpdateObject;

class Update extends Model
{
    use SoftDeletes;
    protected $table = 'updates';
    protected $fillable = ['update_id', 'user_id', 'type', 'reply_to', 'text', 'content', 'date'];
    
    /**
     * Imports Telegram Update objects into the database (stored for debug and development reasons)
     * @param UpdateObject $update
     */
    public function import(UpdateObject $update)
    {
        $updateId = $update->getMessage()->getMessageId();
        $userId = $update->getMessage()->getFrom()->getId();
        $type = $update->getMessage()->getChat()->getType();

        if (! is_null($update->getMessage()->getReplyToMessage())) {
            $reply_to = $update->getMessage()->getReplyToMessage()->getMessageId();
        } else {
            $reply_to = null;
        }
        $text = $update->getMessage()->getText();
        $content = $update->toJson();
        $date = $update->getMessage()->getDate();

        try {
            $this->create([
                'update_id' => $updateId,
                'user_id' => $userId,
                'type' => $type,
                'reply_to' => $reply_to,
                'text' => $text,
                'content' => $content,
                'date' => $date
            ]);
        } catch (QueryException $e) {
            // Already stored
            \Log::error($e->getMessage());
        }
    }
}
