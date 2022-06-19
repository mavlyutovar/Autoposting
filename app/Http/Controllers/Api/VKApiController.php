<?php

namespace App\Http\Controllers\Api;

use ATehnix\VkClient\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VKApiController extends Controller
{
    private $token;
    private $api;
    private $atachments;

    public function __construct($token = null)
    {
        if(!isset($token)) {
            $this->token = env('VK_TOKEN', false);
        }
        else {
            $this->token = $token;
        }

        $this->api = new Client("5.81");
        $this->api->setDefaultToken($this->token);
    }

    public function wallPost($params)
    {
        if(!isset($params['attachments'])) {
            foreach ($this->attachments as $key => $attach) {
                if($key == 0) {
                    $params['attachments'] = $attach;
                }
                else {
                    $params['attachments'] = $attach.", ".$params['attachments'];
                }
            }
        }
        return $this->api->request('wall.post', $params, $this->token);
    }

    public function wallAddPhoto($group_id, $image_path) {
        $path = Storage::disk('public')->path("lastImage.png");
        $image_path = $path;
        $params = ['group_id' => $group_id];
        $uploadServerInfo = $this->api->request('photos.getWallUploadServer', $params);

        $ch = curl_init($uploadServerInfo['response']['upload_url']);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);

        if (class_exists('\CURLFile')) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['file1' => new \CURLFile($image_path)]);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['file1' => "@$image_path"]);
        }

        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $upload = json_decode($data, true);
        $params = null;
        $params ['photo']       = $upload['photo'];
        $params ['server']      = $upload['server'];
        $params ['hash']        = $upload['hash'];
        $params ['group_id']    = $group_id;
        $save = $this->api->request('photos.saveWallPhoto', $params, $this->token);
        $this->atachments[] = sprintf('photo%s_%s', $save[0]['owner_id'], $save[0]['id']);
        return $this->atachments;
    }
}
