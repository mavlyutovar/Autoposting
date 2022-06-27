<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTime extends Model
{
    public function sendPost() {
        $theme = $this->getTheme();
        $group = $this->getGroup();
        if(isset($group) && isset($theme)){
            $theme->initGroup($group);
            return $theme->sendPost();
        }
        else {
            return "Группа или стиль не найдены";
        }
    }

    public function getTheme() {
        return Theme::find($this->theme_id);
    }

    public function getGroup() {
        return Group::find($this->group_id);
    }
}
