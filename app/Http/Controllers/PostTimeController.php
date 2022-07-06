<?php

namespace App\Http\Controllers;

use App\PostTime;
use App\Theme;
use App\ThemeModels\StyleAudio;
use App\ThemeModels\StylePicture;
use App\ThemeModels\StyleText;
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
        $urlSource  = $request->input('urlSource') ?? "";
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
    }

    public function showAllPostTime(Request $request, $group_id)
    {
        return $this->getPostTimes($group_id);
    }

    public function deletePostTime(Request $request, $id)
    {
        $groupid = 0;
        if($id) {
            $postTime   = PostTime::find($id);
            $theme      = Theme::find($postTime->theme_id);
            $groupid    = $postTime->group_id;
            $postTime->delete();
            $theme->delete();
        }
        return $this->getPostTimes($groupid);
    }

    public function sendPostTime(Request $request, $id) {
        $postTime = PostTime::find($id);
        return $postTime->sendPost();
    }

    public function setStatus(Request $request, $id) {
        $postTime   = PostTime::find($id);
        $postTime->status = $request->input('status') ?? "run";
        $groupid    = $postTime->group_id;
        $postTime->save();
        return $this->getPostTimes($groupid);
    }

    public function getPostTimes($group_id = null)
    {
        $postTime = PostTime::where('userid', Auth::id());
        if($group_id) {
            $postTime = $postTime->where('group_id', $group_id);
        }
        $postTime = $postTime->orderBy('time', 'asc')->get();
        foreach ($postTime as $post){
            $post->textCaseName     = "-";
            $post->audioCaseName    = "-";
            $post->audioCaseName    = "-";
            $post->url_source       = "-";
            $post->weak             = json_decode($post->weak);
            $theme                  = $post->getTheme();
            if(isset($theme)){
                //{"textProbability":50,"audioProbability":50,"pictureProbability":100,"textSmile":true,"textRepeat":true,"audioRepeat":false}
                //{"text_style_id":1,"audio_style_id":1,"picture_style_id":1,"textCount":1,"audioCount":2,"pictureCount":5}
                $settings   = json_decode($theme->setting);
                $style      = json_decode($theme->style);

                $getStyle   = StyleText::find($style->text_style_id);
                $post->textCaseName     = $getStyle->name ?? "-";
                $getStyle   = StyleAudio::find($style->audio_style_id);
                $post->audioCaseName    = $getStyle->name ?? "-";
                $getStyle   = StylePicture::find($style->picture_style_id);
                $post->pictureCaseName  = $getStyle->name ?? "-";

                $post->url_source       = $theme->url_source;
                $post->settings         = $settings;
                $post->style            = $style;
            }
            $group = $post->getGroup();
            if(isset($group)){
                $post->groupName = $group->name;
            }
        }
        return $postTime;

    }
}
