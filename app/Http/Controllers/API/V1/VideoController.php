<?php

namespace App\Http\Controllers\API\V1;
use \App\Http\Controllers\API\V1\APIController;
use App\Http\Helpers\Constants;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Http\Helpers\Media;
use App\Jobs\ProcessVideoJob;
use Illuminate\Support\Facades\DB;

class VideoController extends APIController
{
    use Media;

    /**
     * @param Request $request
     * @return mixed
     * Upload a video
     */
    public function upload(Request $request){
        $message = trans("video.error.generic");

        $requestValidationRules = [
            'url' => 'required'
        ];

        if ($errors = $this->requestHasErrors($request, $requestValidationRules)) {
            return $this->sendResponse(
                Constants::HTTP_ERROR,
                $message,
                null,
                $errors
            );
        }


        $videoModel = new Video();
        $videoModel->original_url = $request->input("url");
        $videoModel->metadata = json_encode([]);

        if($videoModel->save()){

            $message = trans("video.success.generic");

                if($videoModel->save()){

                    ProcessVideoJob::dispatch($videoModel);

                    return $this->sendResponse(Constants::HTTP_SUCCESS, $message, $videoModel);
                }

        }

        return $this->sendResponse(Constants::HTTP_ERROR, $message, $errors);

    }

    public function get($id){

        $message = trans("common.error.generic");


        $video = Video::find($id);

        if ($video) {

            $videoResponse = $video->makeHidden('metadata');
            $message = trans("common.success.generic");

            return $this->sendResponse(Constants::HTTP_SUCCESS,
                $message,
                $videoResponse);
        }

        return $this->sendResponse(Constants::HTTP_NOT_FOUND, $message);

    }


    public function list(Request $request){

        $message = trans("common.success.generic");

        $VideoModel = Video::where([]);

        $searchTermConditions = [];

        $perPage = !empty($request->input("per_page")) ?
            $request->input("per_page") : 50;

        if($searchTerm = trim($request->input("search_term"))){
            $VideoModel->orWhere('hashtags', 'like', "%{$searchTerm}%");
            $VideoModel->orWhere('title', 'like', "%{$searchTerm}%");
            $VideoModel->orWhere('description', 'like', "%{$searchTerm}%");
        }

        $videoList = $VideoModel->orderByDesc('created_at')->paginate($perPage);

        return $this->sendResponse(Constants::HTTP_SUCCESS,
            $message,
            $videoList);


    }
}
