<?php

namespace App\Api;

use ATehnix\VkClient\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VKApi extends Model
{
    private $token;
    private $api;
    private $photos;
    public $version = "5.131";
    public $attachments;

    public function __construct($token = null)
    {
        if(!isset($token)) {
            $this->token = env('VK_TOKEN', false);
        }
        else {
            $this->token = $token;
        }

        $this->api = new Client($this->version);
        $this->api->setDefaultToken($this->token);
    }

    public function wallSendPost($params)
    {
        if(!isset($params['attachments'])) {
            if(isset($this->attachments)){
                foreach ($this->attachments as $key => $attach) {
                    if($key == 0) {
                        $params['attachments'] = $attach;
                    }
                    else {
                        $params['attachments'] = $attach.", ".$params['attachments'];
                    }
                }
            }
        }
        return $this->api->request('wall.post', $params, $this->token);
    }

    public function wallAddPhoto($group_id, $srcImage) {
        $image_path = Storage::disk('public')->path($group_id.'lastImage.png');
        copy($srcImage, $image_path);
        $params['group_id']    = $group_id;
        $uploadServerInfo = $this->api->request('photos.getWallUploadServer', $params)['response'];

        $upload = $this->uploadFile($uploadServerInfo['upload_url'], $image_path);
        $upload = json_decode($upload);
        $params['photo']       = $upload->photo;
        $params['server']      = $upload->server;
        $params['hash']        = $upload->hash;
        $params['caption']     = "https://thismood.info/";
        $save = $this->api->request('photos.saveWallPhoto', $params, $this->token)['response'];
        $this->attachments[] = sprintf('photo%s_%s', $save[0]['owner_id'], $save[0]['id']);
        Storage::delete($image_path);
        return $this->attachments;
    }

    public function wallAddPhotoFromPub($group_id, $photo_owner_id) {

        $params['photos'] = $photo_owner_id;
        $params['extended'] = 1;
        $photoInfo = $this->api->request('photos.getById', $params, $this->token);
        $srcImage = $photoInfo['response'][0]["orig_photo"]['url'];
        return $this->wallAddPhoto($group_id, $srcImage);
    }

    public function getPhotosFromPub($group_id) {
        $params['owner_id']    = '-'.$group_id;
        $params['count']       = 5;
        $posts = $this->api->request('wall.get', $params, $this->token);
        if(isset($posts['response']['items'])) {
            foreach ($posts['response']['items'] as $post) {
                if(isset($post['attachments'])) {
                    foreach ($post['attachments'] as $item) {
                        if(isset($item['photo'])) {
                            $this->photos[] = $item['photo']['owner_id']."_".$item['photo']['id']; //ЗАПИСЫВАТЬ В ЛОГАХ
                        }
                    }
                }
            }
        }
        return $this->photos;
    }

    public function wallAddThismoodAudio() {
        $audio = [//Thismood
            "audio-2001177523_68177523",//Eye Contact
            "audio-2001400437_75400437",//Flame
            "audio-2001869873_75869873",//Aqua
            "audio-2001333561_71333561",//In Heart
            "audio-2001852175_88852175",//See
            "audio-2001319977_77319977",//Floating Fish
            "audio-2001583673_71583673",//Universe
            "audio-2001528437_69528437",//To Ride
            "audio-2001351149_108351149",//Show
            "audio-2001192942_68192942",//Day
            "audio-2001192804_68192804",//Night
            "audio-2001097150_83097150",//Inside Me
            "audio-2001528775_69528775",//Will Tell Us
            "audio-2001271402_102271402",//Jaguar
            "audio-2001517124_88517124",//Horses
            "audio-2001413753_83413753",//Cyber Heart
            "audio-2001688590_93688590",//Wings
            "audio-2001343810_71343810",//Forever
            "audio-2001528776_69528776",//Spaceman
            "audio-2001192968_68192968",//Sunrise
            "audio-2001606920_84606920",//Urban
            "audio-2001260841_77260841",//Medusa Dance
            "audio-2001095242_88095242",//In The Sky
            "audio-2001615773_75615773",//Elements
            "audio-2001626994_94626994",//Feel Love
            "audio-2001754700_94754700",//Soul
            "audio-2001388463_103388463",//Snake
            "audio-2001320081_77320081",//Crystal Castle
            "audio-2001938440_107938440",//For Magic
            "audio-2001409831_100409831",//Eagle
            "audio-2001177523_68177523",//Eye Contact
            "audio-2001400437_75400437",//Flame
            "audio-2001869873_75869873",//Aqua
            "audio-2001333561_71333561",//In Heart
        ];
        $gr = array(19, 31, 69, 81, 38, 62);
        $r = rand(0,100);
        if(in_array($r, $gr)){//В случайных порядках будет создаваться пост с тремя аудиозаписями
            $today = getdate();
            $music = $audio[$today["mday"]].", ";
            foreach($audio as $key => $trackId) {
                if($audio[$today["mday"]] == $trackId){
                    if($today["mday"] != $key)
                    {
                        unset($audio[$key]);
                    }
                }
            }
            unset($audio[$today["mday"]]);

            while(count($audio) > 2) {
                unset($audio[array_rand($audio)]);
                shuffle($audio);
            }
            $this->attachments[] = $music . $audio[0].", ".$audio[1];
            return $this->attachments;

        }
        else {//Или обычный пост с 1 треком
            $this->attachments[] = $audio[array_rand($audio)];
            return $this->attachments;
        }
    }

    public function uploadFile($url, $path)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);

        if (class_exists('\CURLFile')) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['file1' => new \CURLFile($path)]);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['file1' => "@$path"]);
        }

        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $data;
    }
}
