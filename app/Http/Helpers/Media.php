<?php
namespace App\Http\Helpers;

use App\Models\Video;
use Illuminate\Support\Facades\Log;

trait Media{

    /**
     * @param $url
     * @param $videoId
     * @return false|array
     * Download a $url
     * return array as file info on success and false on fail
     */
    public function downloadVideo($url, $videoId){
        try{
            $fileInfo = [];

            $videoDownloadsPath =  public_path(Constants::DIR_VIDEO_DOWNLOADS);

            $fileName = time();
            $videoUrl = urldecode($url);
            $cmd = "cd {$videoDownloadsPath} ";
            $cmd .=" && sudo mkdir {$fileName} && cd {$fileName} ";
            $cmd .= '  && sudo youtube-dl -f 18 ' . ' --write-info-json -o "' . $fileName . '.%(ext)s" ' . $videoUrl ." 2>&1" ;

            exec($cmd,$output, $status);
            $downloadStatus = end($output);

            if(strpos($downloadStatus,'[download] 100%')){
                $fileInfo["file_name"] = $fileName;
                $fileInfo["metadata"] = file_get_contents("{$videoDownloadsPath}{$fileName}/{$fileName}.info.json");

                return $fileInfo;
            }

            Log::info("Video (downloadVideo) failed: ", ["video_id" => $videoId]);
            Log::info("CMD output: ", ["cmd_output" => $output]);

            return false;

        }catch (\Exception $e){
            //TODO catch the proper exception
            Log::info("Video (downloadVideo) failed: ", ["video_id" => $videoId]);
            Log::info("Exception output: ", ["exception" => $e->getTraceAsString()]);
            return false;
        }

    }

    /**
     * @param $fileName
     * @param $videoId
     * @return false|string
     * Convert an mp4 fileName to mp3
     * return the converted file name in success and false on fail
     */
    public function extractAudio($fileName, $videoId){
        try{
            $videoDownloadsPath =  public_path(Constants::DIR_VIDEO_DOWNLOADS);

            $cmd = "cd {$videoDownloadsPath}{$fileName} && sudo ffmpeg -i {$fileName}.mp4 {$fileName}.mp3 2>&1";

            exec($cmd, $output, $status);

            if($status !== 0){
                Log::info("Video (extractAudio) failed: ", ["video_id" => $videoId]);
                Log::info("CMD output: ", ["cmd_output" => $output]);

                return false;
            }

            return "{$fileName}.mp3";

        }catch (\Exception $e){
            //TODO catch the proper exception
            Log::info("Video (extractAudio) failed: ", ["video_id" => $videoId]);
            Log::info("Exception output: ", ["exception" => $e->getTraceAsString()]);
            return false;
        }
    }

}