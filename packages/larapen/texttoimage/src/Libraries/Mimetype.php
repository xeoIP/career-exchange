<?php

namespace Larapen\TextToImage\Libraries;

class Mimetype
{
    public static function getMimetype($type)
    {
        switch ($type) {
            case IMAGETYPE_JPEG:
                return 'image/jpeg';
            case IMAGETYPE_GIF:
                return 'image/gif';
            case IMAGETYPE_PNG:
                return 'image/png';
            default:
                return null;
        }
    }
}
