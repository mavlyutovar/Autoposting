<?php

namespace App;

use App\Api\PinterestApi;
use App\Api\VKApi;
use App\Group;
use App\ThemeLogs\LogAudio;
use App\ThemeLogs\LogPicture;
use App\ThemeLogs\LogText;
use App\ThemeModels\Audio;
use App\ThemeModels\Picture;
use App\ThemeModels\StyleAudio;
use App\ThemeModels\Text;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;

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
        set_time_limit(3600);
        $settings   = json_decode($this->setting);
        //{"textProbability":50,"audioProbability":50,"pictureProbability":100,"textSmile":true,"textRepeat":true,"audioRepeat":false}
        $style      = json_decode($this->style);
        //{"text_style_id":1,"audio_style_id":1,"picture_style_id":1,"textCount":1,"audioCount":2,"pictureCount":5}
        $url_source = $this->url_source;
        $groupid    = $this->group->id;
        $getDay     = intval(date("d"));
        $getDay     += (int)date("H");
        if ($getDay > 31) {
            $getDay = $getDay - 31;
        }

        $textLoad           = null;
        $text               = "";
        if($this->probabilityMedia($settings->textProbability)) {
            $texts  = Text::where('style_id', $style->text_style_id)->get() ?? null;
            if(isset($texts)) {
                if(!$settings->textRepeat) {
                    foreach ($texts as $id => $message){
                        $logText  = LogText::where('group_id', $this->id)
                                ->where('value', $message->text_value)->get() ?? null;

                        foreach ($logText as $log){
                            if($message->text_value == $log->value) {
                                if(sizeof($texts) >= 1){
                                    unset($texts[$id]);
                                }
                            }
                        }
                    }
                }
                if(sizeof($texts) > 0){
                    $todayText  = $this->arrayToMonth($texts);

                    $text       = $todayText[$getDay]->string_value;
                    if($settings->textSmile) {
                        $text   = $text." ".$this->getEmoji();
                    }
                    if(isset($text)) {
                        $newLogText             = new LogText();
                        $newLogText->value      = $text;
                        $newLogText->group_id   = $groupid;
                        $newLogText->save();
                    }
                    $textLoad = true;
                    $getDay += rand(1,2);
                    if ($getDay > 31) {
                        $getDay = $getDay - 31;
                    }
                }
            }
        }


        $pictureLoad    = false;
        if($this->probabilityMedia($settings->pictureProbability)) {
            $pictures     = Picture::where('style_id', $style->picture_style_id)->get() ?? null;
            if(isset($pictures)) {
                $pictureCount   = $style->pictureCount;
                $todayPicture   = $this->arrayToMonth($pictures);


                while($pictureCount >= 1){
                    $savePicture = null;
                    $pic_value = $todayPicture[$getDay]->picture_value;
                    if(strpos($pic_value, "pinterest.") !== false){
                        $pins = $this->pinterest->getImagesFromBoard($pic_value);
                        $pins = $this->equalWithPicLogs($pins);
                        //shuffle($pins);
                        $todayPins = $this->arrayToMonth($pins);
                        $savePicture = $todayPins[$getDay];
                        if(isset($savePicture)) {
                            $url = $this->pinterest->getUrlFromPinImage($savePicture);
                            $this->vk->wallAddPhoto($url);
                        }

                    }
                    else {
                        $photos = $this->vk->getPhotosFromPub($pic_value);
                        $photos = $this->equalWithPicLogs($photos);
                        //shuffle($photos);
                        $todayPhotos = $this->arrayToMonth($photos);
                        $savePicture = $todayPhotos[$getDay];
                        if(isset($savePicture)) {
                            $this->vk->wallAddPhotoFromPub($savePicture);
                        }
                    }
                    sleep(2);
                    if(isset($savePicture)) {
                        $newLogPicture              = new LogPicture();
                        $newLogPicture->value       = $savePicture;
                        $newLogPicture->group_id    = $groupid;
                        $newLogPicture->save();
                    }

                    $getDay += rand(7,8);
                    if($getDay > 31) {
                        $getDay = $getDay-31;
                    }
                    $pictureCount--;
                }
                $pictureLoad = true;
            }
        }

        $audioLoad          = true;
        if($this->probabilityMedia($settings->audioProbability)) {
            $audios     = Audio::where('style_id', $style->audio_style_id)->get() ?? null;
            if(isset($audios)) {
                if(!$settings->audioRepeat) {
                    foreach ($audios as $id => $track){
                        $logAudio  = LogAudio::where('group_id', $this->id)
                                ->where('value', $track->audio_value)->get() ?? null;

                        foreach ($logAudio as $log){
                            if($track->audio_value == $log->value) {
                                if(sizeof($audios) >= 1){
                                    unset($audios[$id]);
                                }
                            }
                        }
                    }
                }
                if(sizeof($audios) > 0) {
                    $audioCount = $style->audioCount;
                    if(sizeof($audios) < $audioCount){
                        $audioCount = sizeof($audios);
                    }
                    $todayAudio = $this->arrayToMonth($audios);

                    $countAudio = 0;
                    while ($audioCount >= 1) {
                        $audio = $todayAudio[$getDay]->audio_value;
                        $this->vk->attachments[] = $audio;
                        if (isset($audio)) {
                            $newLogAudio = new LogAudio();
                            $newLogAudio->value = $audio;
                            $newLogAudio->group_id = $groupid;
                            $newLogAudio->save();
                        }

                        $getDay += rand(1,2);
                        if ($getDay > 31) {
                            $getDay = $getDay - 31;
                        }
                        $audioCount--;
                        $countAudio++;
                        if($countAudio > sizeof($audios)){
                            break;
                        }
                    }
                }
            }
        }

        $response = "error 53887";
        if(isset($pictureLoad) || isset($audioLoad) || isset($textLoad)) {
            $publishTime    = time()+(rand(1, 1200)+rand(1, 600))+3600;// +3600 пост появляется за час в отложенных записях

            $this->newPostLog->group_id     = $groupid;
            $this->newPostLog->theme_id     = $this->id;
            $params = [
                'from_group'    => 1,
                'message'       => $text,
                'publish_date'  => $publishTime,
                'copyright'     => $url_source,
            ];
            $response = $this->vk->wallSendPost($params);
            $this->newPostLog->response = json_encode($response);
            $this->newPostLog->save();
        }
        return $response;
    }

    public function equalWithPicLogs(array $pictures = []) {
        foreach ($pictures as $id => $img){
            $logPicture  = LogPicture::where('group_id', $this->group->id)
                    ->where('value', $img)->get() ?? null;

            foreach ($logPicture as $log){
                if($img == $log->value) {
                    if(sizeof($pictures) > 1){
                        unset($pictures[$id]);
                    }
                }
                else break;
            }
        }
        return $pictures;
    }

    public function probabilityMedia($percent = 0) {
        $random = rand(1, 100);
        if($random <= $percent){
            return true;
        }
        return false;
    }

    public function arrayToMonth($array = null) {
        $days   = 31;
        $month  = [];
        $id     = 0;
        $buffer = [];
        foreach ($array as $item){
            $buffer[$id] = $item;

            $id++;
            if($id > sizeof($array)){
                break;
            }
        }
        $id = 0;
        while($days >= 0){
            $month[$days] = $buffer[$id];

            $id++;
            if($id >= sizeof($buffer)) {
                $id = 0;
            }
            $days--;
        }
        return $month;
    }

    public function getEmoji()
    {
        $emoji = array(
            '😉','😊','😍','🤑',
            '😎','😈','👿','💀','👽','🙈','🙉',
            '💓','💞','💕','💔','💛','💚','💙','💜','💥','💫','👇','⛰','🌋','🗻','🏕','🏖','🏜','🏝','🏞','🌟','🌠','🌌',
            '🌈','🔥','💧','🌊','🎈','🎉','💎','🔊','🎧','📷','💸','🔑','','📡','💉','🗿', '🚸','⛔','🔞','🔞','🔞',
        );

        $randomsmile = intval(date("d"));
        $randomsmile += rand(0, 22);
        return $emoji[$randomsmile];
    }
}
