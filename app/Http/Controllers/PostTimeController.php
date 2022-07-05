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
        $setting    = $request->input('setting') ?? null;
        $style      = $request->input('style') ?? null;
        $weak       = $request->input('weak') ?? [];
        $time       = $request->input('time') ?? 13;
        $urlSource  = $request->input('urlSource') ?? 13;
        $group      = $request->input('groupInfo') ?? null;

        $dayOk = false;
        if(isset($setting))
        {
            if(isset($style)) {
                foreach ($weak as $day){
                    if($day === true){
                        $dayOk = true;
                        break;
                    }
                }
                if($dayOk === true) {
                    if(isset($group))
                    {
                        $userid = Auth::id();

                        $theme              = new Theme();
                        $theme->style       = json_encode($style);
                        $theme->setting     = json_encode($setting);
                        $theme->url_source  = $urlSource;
                        $theme->userid      = $userid;
                        $theme->save();

                        $postTime           = new PostTime();
                        $postTime->group_id = $group['id'];
                        $postTime->theme_id = $theme->id;
                        $postTime->time     = $time;
                        $postTime->userid   = $userid;
                        $postTime->weak     = json_encode($weak);
                        $postTime->status   = "run";
                        $postTime->save();
                    }
                    return $this->getPostTimes($group['id']);
                }
                else {
                    return ['error' => 'Выберите хотя бы один день'];
                }
            }
            else {
                return ['error' => 'В запросе отсутствует style'];
            }
        }
        else {
            return ['error' => 'В запросе отсутствует setting'];
        }
        return $this->getPostTimes();
    }

    public function showAllPostTime(Request $request, $group_id)
    {
        return $this->getPostTimes($group_id);
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

    public function getPostTimes($group_id = null)
    {
        $postTime = PostTime::where('userid', Auth::id());
        if($group_id) {
            $postTime = $postTime->where('group_id', $group_id);
        }
        $postTime = $postTime->orderBy('time', 'asc')->get();
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
