<?php
if (!function_exists('yo_get_img_path')) {

    /**
     * 获取缩略图文件名
     * @param type $path
     * @param type $size
     * @return type
     */
    function yo_get_img_path($path, $size = 'thumb') {
        $newPath = '';

        if ($size) {
            $newPath = substr($path, 0, -(strlen($path) - strrpos($path, '.'))) . '_' . $size . substr($path, strrpos($path, '.'));
        } else {
            $newPath = $path;
        }

        return $newPath;
    }

}


if (!function_exists('yo_get_filetype')) {

    /**
     * 返回文件类型名
     * @param type $src
     * @return type
     */
    function yo_get_filetype($src) {
        $result = substr($src, strrpos($src, '.') + 1, strlen($src));
        //返回jpg, png
        return $result;
    }

}