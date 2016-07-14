<?php

namespace Cropan;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $table = 'xp_logs';
    protected $fillable = ['user_id', 'xp', 'concept', 'picture_id'];

    // Relationships
    public function image()
    {
        return $this->belongsTo(Picture::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function addXp(Diary $entry)
    {
        $entry->user->exp += $entry->xp;
        $entry->user->save();
    }

    public static function experienceFromSubmittingPicture(Picture $picture)
    {
        $xp = (int)env('EXP_SUBMIT_IMAGE') * (rand(100, 150) / 100);

        $entry = new Diary();

        // Exp when sending image to telegram bot
        $entry->xp = $xp;
        $entry->user_id = $picture->user->telegram_id;
        $entry->picture_id = $picture->id;
        $entry->concept = "Recibes $entry->xp xp por enviar una " . link_to_action('Pages@vote', 'imagen',
                ['image' => $picture->id]) . " a CropanBot";

        $entry->save();

        Diary::addXp($entry);
    }

    public static function experienceFromImageGettingPublished(Picture $picture)
    {
        $xp = (int)env('EXP_SEND_GROUP_IMAGE') * (rand(100, 110) / 100);

        $entry = new Diary();

        $entry->xp = $xp;
        $entry->user_id = $picture->user->telegram_id;
        $entry->picture_id = $picture->id;
        $entry->concept = "Recibes $entry->xp xp porque tu " . link_to_action('Pages@vote', 'imagen',
                ['image' => $picture->id]) . " ha llegado al grupo de Telegram";

        $entry->save();

        Diary::addXp($entry);
    }

    public static function experienceFromImageGoingToTumblr(Picture $picture)
    {
        $xp = (int)env('EXP_SEND_GROUP_IMAGE') * (rand(100, 120) / 100);

        $entry = new Diary();

        $entry->xp = $xp;
        $entry->user_id = $picture->user->telegram_id;
        $entry->picture_id = $picture->id;
        $entry->concept = "Recibes $entry->xp xp porque tu " . link_to_action('Pages@vote', 'imagen',
                ['image' => $picture->id]) . " ha sido enviada a la cola de Tumblr";

        $entry->save();

        Diary::addXp($entry);
    }

    public static function experienceFromVote(Vote $vote)
    {
        $xp = (int)env('EXP_PER_VOTE');

        $entry = new Diary();

        $entry->xp = $xp;
        $entry->user_id = $vote->user_id;
        $entry->picture_id = $vote->picture_id;
        $entry->concept = "Recibes $entry->xp xp por haber votado una " . link_to_action('Pages@vote', 'imagen',
                ['image' => $vote->picture_id]);

        $entry->save();

        Diary::addXp($entry);
    }

    public static function experienceFromVoteForImageSubmitter(Vote $vote, $isUpdate = false)
    {
        $xp = ($vote->vote) ? (int)env('EXP_POSITIVE_VOTE') : (int)env('EXP_NEGATIVE_VOTE');

        if ($isUpdate) {
            $xp = $xp * 2;
        }

        $entry = new Diary();

        $entry->xp = $xp;
        $entry->user_id = $vote->picture->user_id;
        $entry->picture_id = $vote->picture_id;
        $entry->concept = "Recibes $entry->xp xp por votos recibidos por tu " . link_to_action('Pages@vote', 'imagen',
                ['image' => $vote->picture_id]);

        $entry->save();

        Diary::addXp($entry);
    }

    public static function experienceFromPerfectImage(Picture $picture)
    {
        $xp = (int)env('EXP_PERFECT');

        $entry = new Diary();

        $entry->xp = $xp;
        $entry->user_id = $picture->user_id;
        $entry->picture_id = $picture->id;
        $entry->concept = "Recibes $entry->xp xp porque tu " . link_to_action('Pages@vote', 'imagen',
                ['image' => $picture->id] . " ha conseguido hacer pleno de positivos");

        $entry->save();

        Diary::addXp($entry);
    }

    public static function experienceFromDisgraceImage(Picture $picture)
    {
        $xp = (int)env('EXP_DISGRACE');

        $entry = new Diary();

        $entry->xp = $xp;
        $entry->user_id = $picture->user_id;
        $entry->picture_id = $picture->id;
        $entry->concept = "Recibes $entry->xp xp porque tu " . link_to_action('Pages@vote', 'imagen',
                ['image' => $picture->id] . " ha conseguido hacer pleno de positivos");

        $entry->save();

        Diary::addXp($entry);
    }
}