<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\API\V1;
use App\Http\Helpers\Constants;
use App\Models\Video;
use App\Models\Playlist;
use App\Models\PlaylistVideo;
use Illuminate\Http\Request;
use \App\Http\Controllers\API\V1\APIController;


class PlaylistController extends APIController
{
    /**
     * Create a Playlist
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        $message = trans("playlist.error.generic");

        $requestValidationRules = [
            'title' => 'required'
        ];

        if ($errors = $this->requestHasErrors($request, $requestValidationRules)) {
            return $this->sendResponse(
                Constants::HTTP_ERROR,
                $message,
                null,
                $errors
            );
        }

        $playlist = new Playlist();

        $playlist->title = $request->input("title");
        $playlist->description = $request->input("description");
        $playlist->hashtags = $request->input("hashtags");

        if($playlist->save()){
            $message = trans("playlist.success.created", ["title" => $playlist->title]);
            return $this->sendResponse(Constants::HTTP_SUCCESS, $message, $playlist);
        }

        return $this->sendResponse(Constants::HTTP_ERROR, $message, $errors);
    }


    /**
     * Get a Playlist details
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $id){
        $message = trans("playlist.error.generic");

        $Playlist = Playlist::find($id);

        if(!$Playlist){
            $message = trans("playlist.error.not_found");
            return $this->sendResponse(Constants::HTTP_NOT_FOUND, $message);
        }

        if($Playlist){
            $message = trans("playlist.success.generic");
            return $this->sendResponse(Constants::HTTP_SUCCESS, $message, $Playlist);
        }



        return $this->sendResponse(Constants::HTTP_ERROR, $message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request){

        $message = trans("common.success.generic");

        $playlistModel = Playlist::where([]);

        $perPage = !empty($request->input("per_page")) ?
            $request->input("per_page") : 50;

        if($searchTerm = trim($request->input("search_term"))){
            $playlistModel->orWhere('hashtags', 'like', "%{$searchTerm}%");
            $playlistModel->orWhere('title', 'like', "%{$searchTerm}%");
            $playlistModel->orWhere('description', 'like', "%{$searchTerm}%");
        }

        $playlistList = $playlistModel->orderByDesc('created_at')->paginate($perPage);

        return $this->sendResponse(Constants::HTTP_SUCCESS,
            $message,
            $playlistList);


    }

    public function addVideo(Request $request){
        $message = trans("playlist.error.generic");

        $requestValidationRules = [
            'playlist_id' => 'required|numeric',
            'video_id' => 'required|numeric',
            "is_remove" => 'boolean'

        ];

        if ($errors = $this->requestHasErrors($request, $requestValidationRules)) {
            return $this->sendResponse(
                Constants::HTTP_ERROR,
                $message,
                null,
                $errors
            );
        }

        $isRemove = $request->input("is_remove");

        $playlist = Playlist::find($request->input("playlist_id"));

        $video = Video::find($request->input("video_id"));


        if(!$playlist){
            $message = trans("playlist.error.not_found");
            return $this->sendResponse(Constants::HTTP_NOT_FOUND, $message);
        }

        if(!$video){
            $message = trans("video.error.not_found");
            return $this->sendResponse(Constants::HTTP_NOT_FOUND, $message);
        }

        $videoPlaylistModel = null;
        $playlistVideo = PlaylistVideo::where("playlist_id", $playlist->id)
            ->where("video_id", $video->id)
            ->where("is_deleted", 0)
            ->first();

        //die(print_r($playlistVideo));
        if($playlistVideo and !$isRemove){
            $message = trans("playlist_video.error.already_added",
                ["playlist" => $playlist->title]);
            return $this->sendResponse(Constants::HTTP_ERROR, $message);
        }

        if(!$playlistVideo and $isRemove){
            $message = trans("playlist_video.error.already_removed",
                ["playlist" => $playlist->title]);
            return $this->sendResponse(Constants::HTTP_ERROR, $message);
        }

        if($playlistVideo){
            $videoPlaylistModel =  $playlistVideo;
            $videoPlaylistModel->is_deleted = 1;
        }else{
            $videoPlaylistModel = new PlaylistVideo();
            $videoPlaylistModel->playlist_id = $playlist->id;
            $videoPlaylistModel->video_id = $video->id;
        }

        $videoPlaylistModel->user_id = $this->user ? $this->user->id : null;

        if(!$videoPlaylistModel->save()){
            $message = trans("playlist.error.generic");
            return $this->sendResponse(Constants::HTTP_ERROR, $message);
        }

        !$isRemove ? $message = trans("playlist.success.playlist_video_added",
            ["playlist" => $playlist->title]) :
            $message = trans("playlist.success.playlist_video_removed",
                ["playlist" => $playlist->title]);

        return $this->sendResponse(Constants::HTTP_SUCCESS, $message, $videoPlaylistModel);

    }
}
