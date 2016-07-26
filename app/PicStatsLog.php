<?php

namespace Cropan;

use Illuminate\Database\Eloquent\Model;

class PicStatsLog extends Model
{
    protected $table = 'picture_stats_log';
    protected $fillable = ['date', 'sent', 'published', 'images_positive', 'images_negative', 'votes', 'votes_yes', 'votes_no'];
    protected $primaryKey = 'date';
    public $incrementing = false;
}
