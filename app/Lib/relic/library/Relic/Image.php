<?php
/**
 * @namespace
 */
namespace Relic;

require_once("Relic.php");
require_once("Mime.php");

/**
 * Image class
 *
 * @package  Relic
 */
class Image {

    /**
     * Constructor
     */
    public function __construct($path) {
        $this->image = new \Imagick();
        $this->image->readImage($path);
        $this->path = $path;
    }

    /**
     * Creates a thumbnail given a source image.
     *
     * @param  string $src
     * @param  string $dst
     * @return bool
     */
    public static function thumbnail($src, $dst, $options=array()) {
        $defaults = array(
            'width' => 100,
            'height' => 100,
            'format' => 'png'
        );
        $options = array_merge($defaults, $options);

        if (!is_readable($src)) return false;
        if (Mime::mime($src) == 'application/pdf') $src .= '[0]';
        $image = new static($src);
        $image->format($options['format']);
        $image->scale($options['width'], $options['height']);
        return $image->save($dst);
    }

    /**
     * Create a preview image given a source image.
     *
     * @param  string $src
     * @param  string $dst
     * @return bool
     */
    public static function preview($src, $dst, $options=array()) {
        if (!is_readable($src)) return false;
        if (Mime::mime($src) == 'application/pdf') $src .= '[0]';
        $image = new static($src);
        $image->format('jpeg');
        $image->scale(700, 700);
        return $image->save($dst);
    }

    public function exif() {
        return exif_read_data($this->path);
    }

    /**
     * Scales the image.
     *
     * @param  integer $width
     * @param  integer $height
     * @return bool 
     */
    public function scale($width=100, $height=100) {
        return $this->image->scaleImage($width, $height, true);
    }

    /**
     * @param  string $format
     * @return bool
     */
    public function format($format='png') {
        return $this->image->setImageFormat($format);
    }

    /**
     * 
     * @param  integer $dpi
     * @return bool
     */
    public function resample($dpi=100) {
        return $this->image->resampleImage($dpi, $dpi);
    }

    /**
     *
     * @param  string $path
     * @return bool
     */
    public function save($path=null) {
        return $this->image->writeImage($path);
    }
}
