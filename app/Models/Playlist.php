<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Video;

class Playlist extends Model
{
    use HasFactory;

    public function videos(){
        return $this->belongsToMany('\App\Models\Video', 'playlist_videos',
            'playlist_id', 'video_id');
    }
}
