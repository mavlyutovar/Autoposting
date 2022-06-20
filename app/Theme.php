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

    public function initGroup(Group $group, $token = null)
    {
        $this->vk           = new VKApi($group->group_vkid, $token);
        $this->pinterest    = new PinterestApi();
    }

    public function sendPosts(){
        $url_pic_board = $this->url_picture_board;
        if(strpos($url_pic_board, "pinterest.") !== false){
            $pins = $this->pinterest->getImagesFromBoard($url_pic_board);
            dd($this->pinterest->getUrlFromPinImage($pins[2]));
        }
        else if(strpos($url_pic_board, "vk.") !== false){
            $photos = $this->vk->getPhotosFromPub($this->vk->$group_id);
            return $this->vk->wallAddPhotoFromPub($photos[2]);

        }
    }
}
