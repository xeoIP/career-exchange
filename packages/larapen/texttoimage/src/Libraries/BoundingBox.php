<?php

namespace Larapen\TextToImage\Libraries;

class BoundingBox
{
    public $width;
    public $height;
    public $padding;
    
    public function __construct($width, $height, $padding)
    {
        $this->width = $width;
        $this->height = $height;
        $this->padding = $padding;
    }
}
