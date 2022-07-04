<?php

namespace App\Http\Controllers;

use App\ThemeModels\Audio;
use App\ThemeModels\Picture;
use App\ThemeModels\StyleText;
use App\ThemeModels\StyleAudio;
use App\ThemeModels\StylePicture;
use App\ThemeModels\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function index()
    {
        return view("layouts.theme_list");
    }

    public function AudioCases()
    {
        $cases = [];
        $userid = Auth::id();
        $styles = StyleAudio::where("userid", $userid)->get();
        foreach ($styles as $style) {
            $case['id']     = $style->id;
            $case['name']   = $style->name;
            $audios = Audio::where("style_id", $style->id)->get();
            $orderId = 1;
            foreach ($audios as $item) {
                $case['items'][$orderId] = $item->audio_value;
                $orderId++;
            }
            if($orderId == 1) {
                $style->delete();
                continue;
            }
            $cases[] = $case;
        }
        return $cases;
    }

    public function showAudio(Request $request)
    {
        return $this->AudioCases();
    }

    public function delAudio(Request $request, $id)
    {
        if ($id) {
            $audios = Audio::where('style_id', $id)->get();
            foreach ($audios as $audio) {
                $audio->delete();
            }
            StyleAudio::find($id)->delete();
            return $this->AudioCases();
        }
        return true;
    }

    public function saveAudio(Request $request)
    {
        $audios = (object)$request->all()['audios'] ?? null;
        $id = (int)$request->all()['id'] ?? null;
        $userid = Auth::id();
        if(isset($audios) && isset($audios->items)) {
            shuffle($audios->items);
            if($audios->is == "new") {
                $style = new StyleAudio();
            }
            else if($audios->is == "edit" && isset($id)) {
                $style = StyleAudio::find($id);

                $oldAudios = Audio::where('style_id', $id)->get();
                foreach ($oldAudios as $text) {
                    $text->delete();
                }
            }

            $style->name = $audios->name;
            $style->userid = $userid;
            $style->save();
            $idStyle = $style->id;
            $day = 31;
            foreach ($audios->items as $item) {
                $newAudio                = new Audio();
                $newAudio->style_id      = $idStyle;
                $newAudio->audio_value   = $item;
                $newAudio->case          = $day;
                $newAudio->save();
                $day--;
                if($day < 1) {
                    $day = 31;
                }
            }
        }
        return $this->AudioCases();
    }

    public function TextCases()
    {
        $cases = [];
        $userid = Auth::id();
        $styles = StyleText::where("userid", $userid)->get();
        foreach ($styles as $style) {
            $case['id']     = $style->id;
            $case['name']   = $style->name;
            $texts = Text::where("style_id", $style->id)->get();
            $orderId = 1;
            foreach ($texts as $item) {
                $case['items'][$orderId] = $item->string_value;
                $orderId++;
            }
            if($orderId == 1) {
                $style->delete();
                continue;
            }
            $cases[] = $case;
        }
        return $cases;
    }

    public function showText(Request $request)
    {
        return $this->TextCases();
    }

    public function delText(Request $request, $id)
    {
        if($id) {
            $texts = Text::where('style_id', $id)->get();
            foreach ($texts as $text) {
                $text->delete();
            }
            StyleText::find($id)->delete();
            return $this->TextCases();
        }
        return true;
    }


    public function saveText(Request $request)
    {
        $texts = (object)$request->all()['texts'] ?? null;
        $id = (int)$request->all()['id'] ?? null;
        $userid = Auth::id();
        if(isset($texts) && isset($texts->items)) {
            shuffle($texts->items);
            if($texts->is == "new") {
                $style = new StyleText();
            }
            else if($texts->is == "edit" && isset($id)) {
                $style = StyleText::find($id);

                $oldTexts = Text::where('style_id', $id)->get();
                foreach ($oldTexts as $text) {
                    $text->delete();
                }
            }

            $style->name = $texts->name;
            $style->userid = $userid;
            $style->save();
            $idStyle = $style->id;
            $day = 31;
            foreach ($texts->items as $item) {
                $newText                = new Text();
                $newText->style_id      = $idStyle;
                $newText->string_value  = $item;
                $newText->case          = $day;
                $newText->save();
                $day--;
                if($day < 1) {
                    $day = 31;
                }
            }
        }
        return $this->TextCases();
    }

    public function PictureCases()
    {
        $cases = [];
        $userid = Auth::id();
        $styles = StylePicture::where("userid", $userid)->get();
        foreach ($styles as $style) {
            $case['id']     = $style->id;
            $case['name']   = $style->name;
            $texts = Picture::where("style_id", $style->id)->get();
            $orderId = 1;
            foreach ($texts as $item) {
                $case['items'][$orderId] = $item->picture_value;
                $orderId++;
            }
            if($orderId == 1) {
                $style->delete();
                continue;
            }
            $cases[] = $case;
        }
        return $cases;
    }

    public function showPicture(Request $request)
    {
        return $this->PictureCases();
    }

    public function delPicture(Request $request, $id)
    {
        if ($id) {
            $audios = Picture::where('style_id', $id)->get();
            foreach ($audios as $audio) {
                $audio->delete();
            }
            StylePicture::find($id)->delete();
            return $this->PictureCases();
        }
        return true;
    }

    public function savePicture(Request $request)
    {
        $pictures = (object)$request->all()['pictures'] ?? null;
        $id = (int)$request->all()['id'] ?? null;
        $userid = Auth::id();
        if(isset($pictures) && isset($pictures->items)) {
            shuffle($pictures->items);
            if($pictures->is == "new") {
                $style = new StylePicture();
            }
            else if($pictures->is == "edit" && isset($id)) {
                $style = StylePicture::find($id);

                $oldPictures = Picture::where('style_id', $id)->get();
                foreach ($oldPictures as $text) {
                    $text->delete();
                }
            }

            $style->name = $pictures->name;
            $style->userid = $userid;
            $style->save();
            $idStyle = $style->id;
            $day = 31;
            foreach ($pictures->items as $item) {
                $newPicture                = new Picture();
                $newPicture->style_id      = $idStyle;
                $newPicture->picture_value  = $item;
                $newPicture->case          = $day;
                $newPicture->save();
                $day--;
                if($day < 1) {
                    $day = 31;
                }
            }
        }
        return $this->PictureCases();
    }

}
