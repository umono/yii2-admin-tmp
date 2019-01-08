<?php

use yii\db\Migration;

/**
 * Handles the creation of table `photo`.
 */
class m180614_041028_create_photo_table extends Migration
{
    private  $table =  '{{%photo}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "图片表"';
        }
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull()->defaultValue('')->comment('文件名'),
            'url' => $this->string()->notNull()->defaultValue('')->comment('图片的URL'),
            'user_id'=>$this->integer()->notNull()->defaultValue(0)->comment('用户ID'),
            'type_id' => $this->integer()->notNull()->defaultValue(0)->comment('关联类型的ID'),
            'type_model'=> $this->string()->notNull()->defaultValue('')->comment('类型类'),
            'created_at'=> $this->dateTime()->comment('创建时间'),
            'updated_at'=> $this->dateTime()->comment('修改时间')
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
