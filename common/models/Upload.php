<?php 
namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;

class Upload extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
        ];
    }
    
    public function uploads()
    {
        $images = $this->imageFiles;
        $imgs = [];
        if ($this->validate()) {
            foreach ($images as $file) {
                $path = 'uploads/' . md5($file->baseName.time()) . '.' . $file->extension;
                $res = $file->saveAs($path);
                if ($res) {
                    $imgs[] = '/' . $path;
                } else {
                    continue;
                }
            }
            if (sizeof($images) == sizeof($imgs)) {
                return implode(',', $imgs);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>
