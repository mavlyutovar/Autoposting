<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTime extends Model
{
    public function sendPost() {
        $theme = Theme::find($this->theme_id);
        $group = Group::find($this->group_id);
        $theme->initGroup($group);
        return $theme->sendPost();
    }

    public function getTheme() {
        return Theme::find($this->theme_id);
    }

    public function getGroup() {
        return Group::find($this->group_id);
    }
}
