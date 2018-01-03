<?php
/**
 * Created by PhpStorm.
 * User: macuser
 * Date: 6/22/15
 * Time: 1:40 PM
 */

namespace App\Helpers;

/**
 * Class ImageUtil
 * @package \Framework\Helpers
 */
class ImageUtil {

    /**
     * @param $oldFile
     * @param $newFile
     * @param $dimX
     * @param $dimY
     * @return bool
     */
    public static function makeAvatar ($oldFile, $newFile, $dimX, $dimY) {
        if(!is_file($oldFile)) {
            return false;
        }

        list($_width, $_height, $_image_type) = getimagesize($oldFile);

        if(0 == $_width || 0 == $_height || 0 == $dimX || 0 == $dimY) {
            return false;
        }

//        if($_width<$_height){
//            $tmp=$dimX;
//            $dimX=$dimY;
//            $dimY=$tmp;
//        }

        // return gettype($_image_type);

        //load file
        switch($_image_type) {
            case IMG_GIF:
                $oldFileObj = imagecreatefromgif($oldFile);
                break;

            case IMG_PNG:
            case 3:
                $oldFileObj = imagecreatefrompng($oldFile);
                break;

            case IMG_JPEG:
            case IMG_JPG:
                $oldFileObj = imagecreatefromjpeg($oldFile);
                break;

            case 6:
            case IMG_WBMP:
                $oldFileObj = self::imageCreateFromBmp($oldFile);
                break;

            default:
                $oldFileObj = null;
                break;
        }
        if(!is_resource($oldFileObj)) {
            return false;
        }

        //create new file obj
        $newFileObj = imagecreatetruecolor($dimX, $dimY);

        $_ratio_old = $_width / $_height;
        $_ratio_new = $dimX / $dimY;

        $_x1 = $_y1 = $_x2 = $_y2 = 0;

        if($_ratio_old > $_ratio_new) {
            // 老图片太宽
            $_tmp_width = intval($_height * $_ratio_new);
            $_x1 = intval(($_width - $_tmp_width)/2);
            $_y1 = 0;

            imagecopyresampled($newFileObj, $oldFileObj, 0, 0, $_x1, $_y1, $dimX, $dimY, $_tmp_width, $_height);
        } else if ($_ratio_old == $_ratio_new) {
            // 等比
            imagecopyresampled($newFileObj, $oldFileObj, 0, 0, 0, 0, $dimX, $dimY, $_width, $_height);
        } else if ($_ratio_old < $_ratio_new) {
            // 老图片太窄
            $_tmp_height = intval($_width / $_ratio_new);
            $_x1 = 0;
            $_y1 = intval(($_height - $_tmp_height) / 2);
            imagecopyresampled($newFileObj, $oldFileObj, 0, 0, $_x1, $_y1, $dimX, $dimY, $_width, $_tmp_height);
        }

        $_arr_file_name = explode('.', $newFile);
//        if('jpg' != strtolower(last($_arr_file_name))) {
//            array_pop($_arr_file_name);
//            array_push($_arr_file_name, 'jpg');
//        }

        // save image obj to file
        imagejpeg($newFileObj, implode('.', $_arr_file_name), 95);

        return true;
    }

    /**
     * @param $filepath
     * @return bool|resource
     */
    public static function imageCreateFromAny($filepath) {
        if(!is_file($filepath)) {
            return false;
        }

        list($_width, $_height, $_image_type) = getimagesize($filepath);

        switch ($_image_type) {
            case IMG_GIF :
                return imageCreateFromGif($filepath);
                break;
            case IMG_JPG :
            case IMG_JPEG :
                return imageCreateFromJpeg($filepath);
                break;
            case IMG_PNG :
                return imageCreateFromPng($filepath);
                break;
            case 6 :
                return imageCreateFromBmp($filepath);
                break;
        }

        return false;
    }

    public static function imageSaveFromAnyToJpg($srcObj, $fileName) {
        $_file_type = strtolower(trim(last(explode('.', $fileName))));

        switch($_file_type) {
            case 'gif':
                imagegif($srcObj, $fileName);
                break;

            case 'jpg':
            case 'jpeg':
                imagejpeg($srcObj, $fileName);
                break;

            case 'png':
                imagepng($srcObj, $fileName);
                break;

            case 'bmp':
                break;

            default:
                break;
        }
        // no need to return anything
    }

    public static function imageCreateFromBmp($p_sFile) {
        $file = fopen($p_sFile, "rb");
        $read = fread($file, 10);
        while (!feof($file) && ($read <> ""))
            $read .= fread($file, 1024);
        $temp = unpack("H*", $read);
        $hex = $temp[1];
        $header = substr($hex, 0, 108);
        if (substr($header, 0, 4) == "424d") {
            $header_parts = str_split($header, 2);
            $width = hexdec($header_parts[19] . $header_parts[18]);
            $height = hexdec($header_parts[23] . $header_parts[22]);
            unset($header_parts);
        }
        $x = 0;
        $y = 1;
        $image = imagecreatetruecolor($width, $height);
        $body = substr($hex, 108);
        $body_size = (strlen($body) / 2);
        $header_size = ($width * $height);
        $usePadding = ($body_size > ($header_size * 3) + 4);
        for ($i = 0; $i < $body_size; $i+=3) {
            if ($x >= $width) {
                if ($usePadding)
                    $i += $width % 4;
                $x = 0;
                $y++;
                if ($y > $height)
                    break;
            }
            $i_pos = $i * 2;
            $r = hexdec($body[$i_pos + 4] . $body[$i_pos + 5]);
            $g = hexdec($body[$i_pos + 2] . $body[$i_pos + 3]);
            $b = hexdec($body[$i_pos] . $body[$i_pos + 1]);
            $color = imagecolorallocate($image, $r, $g, $b);
            imagesetpixel($image, $x, $height - $y, $color);
            $x++;
        }
        unset($body);
        return $image;
    }

    /**
     * 根据图片文件名,获取不同类型(尺寸)的文件名,
     * 比如: getImageFileName('sample.jpg','small'), 返回 'sample-small.jpg'
     * @param $file_name 原始文件名
     * @param $type: small, medium, large
     * @return string 处理过的文件名(如果原文件名没有后缀, 则直接返回原文件名)
     */
    public static function getImageFileName($file_name, $type){
        if(!isset($file_name) || $file_name == null || empty($file_name)){
            return $file_name;
        }
        $position = strrpos($file_name,'.');
        if($position === false){
            return $file_name;
        } else {
            $temp_arr = str_split($file_name, $position);
            $new_file_name = $temp_arr[0]. '-' . $type . $temp_arr[1];
            return $new_file_name;
        }
    }
}