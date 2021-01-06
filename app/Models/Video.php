<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $hidden = ["metadata"];

    public function playlists(){
        return $this->belongsToMany('\App\Models\Playlist', 'playlist_videos',
            'video_id', 'playlist_id');
    }
}
