<?php
/**
 * Mime class
 *
 * Static methods to return MIME type information given file paths.
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class Mime {
    /* MIME-type translation array */
    public static $mimes = array(
        'image' => array(
            'image/png' => 'png',
            'image/jpeg' => 'jpeg',
            'image/jpg' => 'jpg',
            'image/gif' => 'gif',
        ),
        'document' => array(
            'application/pdf' => 'pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/msword' => 'doc',
            'text/plain' => 'plaintext',
            'text/richtext' => 'richtext',
            'text/rtf' => 'rtf'
        ),
        'video' => array(
            'video/mpeg' => 'mpeg',
            'video/msvideo' => 'avi',
            'video/quicktime' => 'mov'
        ) 
    );

    /**
     * Get the file extension of a path using its MIME-type.
     *
     * @param path
     */
    static function getExt($path) {
        $mime = self::getMime($path);
        # Strip any ;* info
        $mime = array_shift(explode(';', $mime));
        $all = array_merge(
            self::$mimes['image'],
            self::$mimes['document'],
            self::$mimes['video']
        );
        if (array_key_exists($mime, $all)) {
            return $all[$mime];
        }
    }

    /**
     * Get the MIME type of a file given its path.
     *
     * @param path
     */
    static function getMime($path) {
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME);
            return finfo_file($finfo, $path);
        } else if (function_exists('file_info')) {
            return mime_content_type($path);
        } 
        return 'unknown';
    }
}
