<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTime extends Model
{
    public function sendPost() {
        $theme = Theme::find($this->theme_id);
        $group = Group::find($this->group_id);
        $theme->initGroup($group);
        dd($theme->sendPost());
        return ;
    }

    public function sendAllPost() {
        $pos = PostTime::find(18);
        $theme = Theme::find($pos->theme_id);
        $group = Group::find($pos->group_id);
        $theme->initGroup($group);
        dd($theme->sendPost());
        return ;
    }

    public function getTheme() {
        return Theme::find($this->theme_id);
    }

    public function getGroup() {
        return Group::find($this->group_id);
    }
}
