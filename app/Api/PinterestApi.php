<?php

namespace App\Api;

use DOMDocument;
use Illuminate\Database\Eloquent\Model;

class PinterestApi extends Model
{
    public function getImagesFromBoard($link)
    {
        $html       = file_get_contents($link);
        $dom        = new DOMDocument;
        $srcPins    = null;

        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $Hrefs  = $dom->getElementsByTagName('a');
        foreach ($Hrefs as $a) {
            $href = $a->getAttribute("href");
            if(strpos($href, "/pin/") !== false){
                $srcPins[] = "https://www.pinterest.ru".$href;
            }
        }
        return $srcPins;
    }

    public function getUrlFromPinImage($pinUrl)
    {
        $html   = file_get_contents($pinUrl);
        $dom    = new DOMDocument;
        $dom->loadHTML($html);

        $images = $dom->getElementsByTagName('link');
        foreach ($images as $image) {
            $href = $image->getAttribute("href");
            if(strpos($href, "/originals/") !== false){
                return $href;
            }
        }
        return null;
    }
}
