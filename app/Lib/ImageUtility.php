<?php

include_once('Mime.php');

class ImageUtility {

    public function __construct() {
        $this->image = new Imagick();
    }

    public static function thumbnail($src, $dst) {
        $image = new static();
        if (!$image->read($src)) return false;
        if (Mime::getMime($src) == 'application/pdf')
            $src .= '[0]';
        $image->format('png');
        $image->scale(100, 100);
        return $image->write($dst);
    }

    public static function preview($src, $dst) {
        $image = new static();
        if (!$image->read($src)) return false;
        $image->format('jpeg');
        $image->scale(700, 700);
        $image->resample(100);
        return $image->write($dst);
    }

    public function scale($width=100, $height=100) {
        return $this->image->scaleImage($width, $height, true);
    }

    public function format($format='png') {
        return $this->image->setImageFormat($format);
    }

    public function resample($dpi=100) {
        return $this->image->resampleImage($dpi, $dpi);
    }

    public function read($path) {
        try {
            return $this->image->readImage($path);
        } catch (Exception $e) {
            return false;
        }
    }

    public function write($path=null) {
        return $this->image->writeImage($path);
    }
}
