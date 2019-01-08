<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin_log`.
 */
class m180604_044751_create_admin_log_table extends Migration
{
    private  $table =  '{{%admin_log}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "操作日志表"';
        }
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->comment('日志ID'),
            'type' => $this->integer(2)->notNull()->defaultValue(1)->comment('日志类型'),
            'controller' => $this->string(64)->notNull()->defaultValue('')->comment('控制器'),
            'action' => $this->string(64)->notNull()->defaultValue('')->comment('方法'),
            'url' => $this->string(100)->notNull()->defaultValue('')->comment('请求地址'),
            'index' => $this->text()->notNull()->comment('请求的参数数据'),
            'params' => $this->text()->notNull()->comment('请求返回的数据'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
            'created_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建用户'),
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
