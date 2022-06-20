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
        $this->vk           = new VKApi($group->group_vkid, $token);
        $this->pinterest    = new PinterestApi();
        $this->newPostLog   = new PostLog();
        $this->group        = $group;
    }

    public function sendPost(){
        $url_pic_board  = $this->url_picture_board;
        $pictureLoad    = null;
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
        if(isset($pictureLoad)) {
            $publishTime        = time()+(rand(1, 59)*rand(1, 3)*60);
            $text   =   "skaaaaaaaaaay";
            $audio  =   $this->vk->wallAddThismoodAudio();

            $this->newPostLog->text_value = $text;
            $this->newPostLog->group_id = $this->group->id;
            $this->newPostLog->theme_id = $this->id;
            $this->newPostLog->audio_value = $audio;
            $params = [
                'from_group'    => 1,
                'message'       => $text,
                'publish_date'  => $publishTime,
            ];
            $response = $this->vk->wallSendPost($params);
            $this->newPostLog->save();
            return $response;
        }

    }
    public function equalWithLogs($items) {
        $postLogs = PostLog::where('group_id', $this->group->group_vkid)->get();
        foreach ($postLogs as $log) {
            foreach ($items as $key => $value) {
                if($log->pic_value == $value
                || $log->audio_value == $value
                || $log->text_value == $value) {
                    unlink($items[$key]);
                }
            }
        }
        shuffle($items);
        return $items;
    }
}
