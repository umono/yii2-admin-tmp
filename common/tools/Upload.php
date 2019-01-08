<?php

namespace common\tools;

use api\models\BaseModel;
use Yii;
use \yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * This is the model class for table "{{%photo}}".
 *
 * @property int $id
 * @property string $title 文件名
 * @property string $url 图片的URL
 * @property int $user_id 用户ID
 * @property int $type_id 关联类型的ID
 * @property string $type_model 类型类
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Upload extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%photo}}';
    }

    public $_type_id;
    public $_type_model;
    /**
     * @var
     */
    public $imageFile;

    public $photo;

    public $imageFiles;

    /**
     * @var string 定义路径
     */
    public $uploadPath  = '/web/uploads/';

    // 设置应用场景
    public function scenarios()
    {
        return [
            'more'   => ['face'],
            'url' => ['url'],
            'photo' => ['face'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_type_id','_type_model'],'safe'],
            [['photo'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'gif', 'jpeg'],'on' => 'photo'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'gif', 'jpeg'],'on' => 'url'],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'gif', 'jpeg'],'maxFiles' => 8,'on' => 'more'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'url' => Yii::t('app', 'Url'),
            'user_id' => Yii::t('app', 'User ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'type_model' => Yii::t('app', 'Type Model'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    //多文件上传
    public function uploads($dirPath)
    {
        // 定义好保存文件目录，目录不存在那么创建
        $dirName = $dirPath.$this->uploadPath;
        FileHelper::createDirectory($dirName);
        if (!file_exists($dirName)) {
            throw new \UnexpectedValueException('目录创建失败:' . $dirName);
        }

        //验证
        if ($this->validate()) {
            $arr = [];
            foreach ($this->imageFiles as $file) {
                $name = $dirName . md5($file->baseName.time()) . '.' . $file->extension;
                $url = '/uploads/'. md5($file->baseName.time()) . '.' . $file->extension;
                if($file->saveAs($name)){
                    if ($this->insertFile($url,$dirPath)) {
                        $arr[] = $url;
                    }
                }else{
                    throw new \UnexpectedValueException('保存图片失败');
                }
            }
            return json_encode($arr);
        } else {
            throw new \UnexpectedValueException($this->getFirstError('more'));
        }
    }

    //单文件上传 一
    public function upload($dirPath)
    {
        // 定义好保存文件目录，目录不存在那么创建
        $dirName = $dirPath.$this->uploadPath;
        FileHelper::createDirectory($dirName);
        if (!file_exists($dirName)) {
            throw new \UnexpectedValueException('目录创建失败:' . $dirName);
        }

        //验证保存
        if ($this->validate()) {
            $file = $dirName . md5($this->imageFile->baseName.time()) . '.' . $this->imageFile->extension;
            $url = '/uploads/'. md5($this->imageFile->baseName.time()) . '.' . $this->imageFile->extension;
            if($this->imageFile->saveAs($file)){
                $this->insertFile($url,$dirPath);
                return $url;
            }
            throw new \UnexpectedValueException('操作失败');
        } else {
            throw new \UnexpectedValueException($this->getFirstError('avatar'));
        }
    }

    //单文件上传 二 一张一张上传
    public function photo($dirPath)
    {
        // 定义好保存文件目录，目录不存在那么创建
        $dirName = $dirPath.$this->uploadPath;
        FileHelper::createDirectory($dirName);
        if (!file_exists($dirName)) {
            throw new \UnexpectedValueException('目录创建失败:' . $dirName);
        }

        //验证保存
        if ($this->validate()) {
            $file = $dirName . md5($this->photo->baseName.time()) . '.' . $this->photo->extension;
            $url = '/uploads/'. md5($this->photo->baseName.time()) . '.' . $this->photo->extension;
            if($this->photo->saveAs($file)){
                return $this->insertPhoto($url,$dirPath);
            }
            throw new \UnexpectedValueException('上传图片操作失败');
        } else {
            throw new \UnexpectedValueException($this->getFirstError('avatar'));
        }
    }


    public function insertFile($url,$dirPath)
    {
        //如果上传的文件是头像
        if ($this->scenario == 'url'){
            $avatar =  Upload::findOne([
                'type_id' =>$this->_type_id,
                'type_model'=>$this->_type_model,
                'user_id' => Yii::$app->user->id,
            ]);
            if (!empty($avatar)){
                $file = $avatar->url;
                $avatar->scenario = 'url';
                $avatar->url = $url;
                $result = $dirPath.'/web/'.$file;
                if(file_exists($result)){
                    unlink($result);
                }
                return $avatar->save()?$avatar->url:$avatar->errors;
            }else{
                $upload = new Upload();
                $upload->scenario = 'url';
                $upload->title = '';
                $upload->url = $url;
                $upload->user_id = Yii::$app->user->id;
                $upload->type_id = $this->_type_id;
                $upload->type_model = $this->_type_model;
                $upload->created_at = date('Y-m-d H:i:s');
                $upload->updated_at = date('Y-m-d H:i:s');
                return $upload->save()?$upload->url:$upload->errors;
            }
        }
        else{
            $upload = new Upload();
            $upload->scenario = 'url';
            $upload->title = '';
            $upload->url = $url;
            $upload->user_id = Yii::$app->user->id;
            $upload->type_id = $this->_type_id;
            $upload->type_model = $this->_type_model;
            $upload->created_at = date('Y-m-d H:i:s');
            $upload->updated_at = date('Y-m-d H:i:s');
            if($upload->save())
                return $upload->url;
            throw new UnauthorizedHttpException($upload->errors);
        }
    }


    public function insertPhoto($url,$dirPath)
    {
        //如果上传的文件是头像
        $upload = new Upload();
        $upload->scenario = 'photo';
        $upload->title = '';
        $upload->url = $url;
        $upload->user_id = Yii::$app->user->id;
        $upload->type_id = $this->_type_id;
        $upload->type_model = $this->_type_model;
        $upload->created_at = date('Y-m-d H:i:s');
        $upload->updated_at = date('Y-m-d H:i:s');
        if($upload->save())
            return $upload->url;
        throw new UnauthorizedHttpException($upload->errors);
    }

    public static function findModel($id)
    {
        if (($model = self::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

    }
}
