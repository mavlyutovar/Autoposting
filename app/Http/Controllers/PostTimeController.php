<?php

namespace App\Http\Controllers;

use App\Api\PinterestApi;
use App\Group;
use App\PostTime;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostTimeController extends Controller
{

    public function index()
    {
        return view("layouts.post_time_list", [ 'data' => $this->getPostTimes()]);

    }

    public function addNewPostTime(Request $request)
    {
        $themeId = $request->input('themeId') ?? -1;
        $groups = $request->input('groups') ?? [];
        $weak = $request->input('weak') ?? [];
        $time = $request->input('time') ?? 13;
        $dayOk = false;
        if($themeId > -1)
        {
            if(count($groups)) {
                foreach ($weak as $day){
                    if($day === true){
                        $dayOk = true;
                        break;
                    }
                }
                if($dayOk === true) {
                    foreach ($groups as $groupId){
                        if(isset($groupId))
                        {
                            $postTime           = new PostTime();
                            $postTime->group_id = $groupId;
                            $postTime->theme_id = $themeId;
                            $postTime->time     = $time;
                            $postTime->userid   = Auth::id();
                            $postTime->weak     = json_encode($weak);
                            $postTime->save();
                        }
                    }
                    return $this->getPostTimes();
                }
                else {
                    return ['error' => 'Выберите хотя бы один день'];
                }
            }
            else {
                return ['error' => 'Добавьте группу'];
            }
        }
        else {
            return ['error' => 'Выберите тему'];
        }
        return $this->getPostTimes();
    }

    public function showAllPostTime()
    {
        return $this->getPostTimes();
    }

    public function deletePostTime(Request $request, $id)
    {
        if($id) {
            PostTime::find($id)->delete();
        }
        return $this->getPostTimes();
    }

    public function sendPostTime(Request $request, $id) {
        $postTime = PostTime::find($id);
        $postTime->sendPost();
        return "";
    }

    public function getPostTimes()
    {
        $postTime = PostTime::where('userid', Auth::id())->get();
        foreach ($postTime as $post){
            $post->themeName = "-";
            $post->groupName = "-";
            $post->weak = json_decode($post->weak);
            $theme = $post->getTheme();
            if(isset($theme)){
                $post->themeName = $theme->name;
            }
            $group = $post->getGroup();
            if(isset($group)){
                $post->groupName = $group->name;
            }
        }
        return $postTime;

    }
}
