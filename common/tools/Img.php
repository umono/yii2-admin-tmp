<?php
/**
 * Created by PhpStorm.
 * User: Black.Mr
 * Date: 2018/8/27
 * Time: 10:54
 */

namespace common\tools;


class Img
{
    protected  $path;

    public function __construct($name,$path,$width,$height)
    {
        return $this->imagecropper($name,$path,$width,$height);
    }

    /**
     * @param $file_name 图片名称
     * @param $source_path 原图路径
     * @param $target_width 需要裁剪的宽
     * @param $target_height 需要裁剪的高
     * @return bool
     */
    /**
     * @param $file_name
     * @param $source_path
     * @param $target_width
     * @param $target_height
     * @return bool
     */
    function imagecropper($file_name,$source_path, $target_width, $target_height)
    {
        $source_info = getimagesize($file_name);
        $source_width = $source_info[0];
        $source_height = $source_info[1];
        $source_mime = $source_info['mime'];
        $source_ratio = $source_height / $source_width;
        $target_ratio = $target_height / $target_width;

        // 源图过高
        if ($source_ratio > $target_ratio)
        {
            $cropped_width = $source_width;
            $cropped_height = $source_width * $target_ratio;
            $source_x = 0;
            $source_y = ($source_height - $cropped_height) / 2;
        }
        // 源图过宽
        elseif ($source_ratio < $target_ratio)
        {
            $cropped_width = $source_height / $target_ratio;
            $cropped_height = $source_height;
            $source_x = ($source_width - $cropped_width) / 2;
            $source_y = 0;
        }
        // 源图适中
        else
        {
            $cropped_width = $source_width;
            $cropped_height = $source_height;
            $source_x = 0;
            $source_y = 0;
        }

        switch ($source_mime)
        {
            case 'image/gif':
                $source_image = imagecreatefromgif($file_name);
                break;

            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($file_name);
                break;

            case 'image/png':
                $source_image = imagecreatefrompng($file_name);
                break;

            default:
                return false;
                break;
        }

        $target_image = imagecreatetruecolor($target_width, $target_height);
        $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);

        // 裁剪
        imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
        // 缩放
        imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);

        //保存图片到本地(两者选一)
//        $randNumber = mt_rand(00000, 99999). mt_rand(000, 999);
//        $fileName = substr(md5($randNumber), 8, 16) .".png";
        $ok = imagepng($target_image,$source_path);
        imagedestroy($target_image);
        return $ok;
//        var_dump(imagedestroy($target_image));

        //直接在浏览器输出图片(两者选一)
        // header('Content-Type: image/jpeg');
        // imagepng($target_image);
        // imagedestroy($target_image);
        // imagejpeg($target_image);
        // imagedestroy($source_image);
        // imagedestroy($target_image);
        // imagedestroy($cropped_image);
    }

    //调用
    //imagecropper('./img033.jpg',300,300);
    //imagecropper('./img033.jpg',55,55);



}