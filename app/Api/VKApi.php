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
    private $group_id;
    public $version = "5.131";
    public $attachments;

    public function __construct($group_id, $token = null)
    {
        $this->group_id = $group_id;
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
        $params['owner_id']= '-'.$this->group_id;
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

    public function wallAddPhoto($srcImage) {
        $image_path = Storage::disk('public')->path($this->group_id.'lastImage.png');
        copy($srcImage, $image_path);
        $params['group_id']    = $this->group_id;
        $uploadServerInfo = $this->api->request('photos.getWallUploadServer', $params)['response'];

        $upload = $this->uploadFile($uploadServerInfo['upload_url'], $image_path);
        $upload = json_decode($upload);
        $params['photo']       = $upload->photo;
        $params['server']      = $upload->server;
        $params['hash']        = $upload->hash;
        //$params['caption']     = "https://thismood.info/";
        $save = $this->api->request('photos.saveWallPhoto', $params, $this->token)['response'];
        $this->attachments[] = sprintf('photo%s_%s', $save[0]['owner_id'], $save[0]['id']);
        Storage::delete($image_path);
        return $this->attachments;
    }

    public function wallAddPhotoFromPub($photo_owner_id) {

        $params['photos'] = $photo_owner_id;
        $params['extended'] = 1;
        $photoInfo = $this->api->request('photos.getById', $params, $this->token);
        $srcImage = $photoInfo['response'][0]["orig_photo"]['url'];
        return $this->wallAddPhoto($srcImage);
    }

    public function getPhotosFromPub($group_id) {
        $params['owner_id']    = '-'.$group_id;
        $params['count']       = 5;
        $posts = $this->api->request('wall.get', $params, $this->token);
        if(isset($posts['response']['items'])) {
            foreach ($posts['response']['items'] as $post) {
                if(isset($post['copyright'])) {
                    continue;
                }
                if(isset($post['attachments']) && $post['marked_as_ads'] == 0) {
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
}
