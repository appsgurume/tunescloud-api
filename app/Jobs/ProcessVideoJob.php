<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Helpers\Constants;
use App\Http\Helpers\Media;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,
        Media;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $videoModel;

    public function __construct(Video $videoModel)
    {
        $this->videoModel = $videoModel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Download the url
         */

        $downloadedFileInfo = $this->downloadVideo($this->videoModel->original_url, $this->videoModel->id);

        /**
         * Extract audio
         */
        $extractedAudioFile = $downloadedFileInfo ?
            $this->extractAudio($downloadedFileInfo['file_name'], $this->videoModel->id) :
            null;

        //TODO: add status column in videos table to update the status to (in progress and done)

        if($extractedAudioFile){

            $audioUrl = Constants::DIR_VIDEO_DOWNLOADS . $downloadedFileInfo['file_name'] . "/{$extractedAudioFile}";

            $this->videoModel->audio_url = url($audioUrl) ;
            $this->videoModel->metadata = $downloadedFileInfo['metadata'];
            $this->videoModel->status = Constants::VIDEO_JOB_STATUS_SUCCESS;
            $this->videoModel->save();

        }else{
            $this->videoModel->status = Constants::VIDEO_JOB_STATUS_ERROR;
            $this->videoModel->save();
        }
    }
}
