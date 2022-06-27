<?php

namespace App;

use App\Api\PinterestApi;
use App\Api\VKApi;
use App\Group;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    private $vk;
    private $pinterest;
    private $group;
    private $newPostLog;

    public function initGroup(Group $group, $token = null)
    {
        $token = 'a3e9d2d3eea7a80e1fbfacbd20ce3a9b516a3d44688533bd708733fd9eff3e8fe13e0f09638987f094639';
        $this->vk           = new VKApi($group->group_vkid, $token);
        $this->pinterest    = new PinterestApi();
        $this->newPostLog   = new PostLog();
        $this->group        = $group;

    }

    public function sendPost(){
        $settings   = json_decode($this->setting);

        $audioLoad  = true;
        $audio      = "";
        if($this->probabilityMedia($settings->audioProbability)) {
            $url_audio_board    = json_decode($this->url_audio_board);
            if(isset($url_audio_board->audioId)) {

                $audios  = (array)$url_audio_board->audioId;
                shuffle($audios);
                $this->vk->attachments[] = $audios[0];
                $audio  = $audios[0];
            }
            if($url_audio_board->rndAudio) {
                $audio  = $this->vk->wallAddThismoodAudio();
            }
        }

        $textLoad   = null;
        $text       = "";
        if($this->probabilityMedia($settings->textProbability)) {
            $texts  = json_decode($this->text);
            if(isset($texts->text)) {
                if(!isset($texts->rndText)) {
                    $text     = "🖤";
                    $textLoad = true;
                }
                $texts      = $this->equalWithLogs($texts->text);
                if(isset($texts[0])) {
                    $text       = $texts[0];
                    $textLoad = true;
                }
            }
        }


        $url_pic_board  = $this->url_picture_board;
        $pictureLoad    = false;
        if($this->probabilityMedia($settings->pictureProbability)) {
            if(isset($url_pic_board))
            {
                if(strpos($url_pic_board, "pinterest.") !== false){
                    $pins = $this->pinterest->getImagesFromBoard($url_pic_board);
                    $pins = $this->equalWithLogs($pins);
                    if(isset($pins[1])) {
                        $this->newPostLog->pic_value = $pins[1];
                        $url = $this->pinterest->getUrlFromPinImage($pins[1]);
                        $this->vk->wallAddPhoto($url);
                        $pictureLoad = true;
                    }
                }
                else {
                    $photos = $this->vk->getPhotosFromPub($url_pic_board);
                    $photos = $this->equalWithLogs($photos);
                    if(isset($photos[1])) {
                        $this->newPostLog->pic_value = $photos[2];
                        $this->vk->wallAddPhotoFromPub($photos[2]);
                        $pictureLoad = true;
                    }
                }
            }
        }

        if(isset($pictureLoad) || isset($audioLoad) || isset($textLoad)) {
            $publishTime    = time()+(rand(1, 1200)+rand(1, 600));

            $this->newPostLog->text_value   = $text;
            $this->newPostLog->group_id     = $this->group->id;
            $this->newPostLog->theme_id     = $this->id;
            $this->newPostLog->audio_value  = $audio;
            $params = [
                'from_group'    => 1,
                'message'       => $text,
                'publish_date'  => $publishTime,
            ];
            $response = $this->vk->wallSendPost($params);
            $this->newPostLog->response = json_encode($response);
            $this->newPostLog->save();
            return $response;
        }


    }

    public function equalWithLogs($items) {
        $postLogs = PostLog::where('group_id', $this->group->id)->get();
        foreach ($postLogs as $log) {
            foreach ($items as $key => $value) {
                if($log->pic_value == $value
                    || $log->audio_value == $value
                    || $log->text_value == $value) {
                    unset($items[$key]);
                }
            }
        }
        shuffle($items);
        return $items;
    }

    public function probabilityMedia($percent = 0) {
        $random = rand(1, 100);
        if($random <= $percent){
            return true;
        }
        return false;
    }
}
