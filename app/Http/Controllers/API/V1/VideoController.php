<?php

namespace App\Http\Controllers\API\V1;
use \App\Http\Controllers\API\V1\APIController;
use App\Http\Helpers\Constants;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Http\Helpers\Media;
use App\Jobs\ProcessVideoJob;


class VideoController extends APIController
{
    use Media;

    /**
     * @param Request $request
     * @return mixed
     * Upload a video
     */
    public function upload(Request $request){
        $message = trans("common.error.generic");

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

            $message = trans("common.success.generic");

                if($videoModel->save()){

                    ProcessVideoJob::dispatch($videoModel);

                    return $this->sendResponse(Constants::HTTP_SUCCESS, $message, $videoModel);
                }

        }

        return $this->sendResponse(Constants::HTTP_ERROR, $message, $errors);

    }
}
