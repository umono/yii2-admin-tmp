<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m180601_035116_create_admin_table extends Migration
{
    private $table = '{{%admin}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "管理员用户表"';
        }
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'email' => $this->string(64)->notNull()->unique(),
            'name' => $this->string(30)->notNull()->defaultValue(''),
            'avatar' => $this->string()->notNull()->defaultValue('')->comment('头像'),
            'last_time' => $this->dateTime()->comment('上一次登录时间'),
            'last_ip' => $this->char(15)->defaultValue('')->notNull()->comment('上一次登录的IP'),
            'now_time' => $this->dateTime()->comment('当前登录时间'),
            'now_ip' => $this->char(15)->defaultValue('')->notNull()->comment('当前登录的IP'),
            'role' => $this->string(64)->notNull()->defaultValue('')->comment('管理员角色'),
            'address' => $this->string(100)->defaultValue('')->comment('地址信息'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10)->comment('状态'),
            'created_at' => $this->dateTime()->notNull(),
            'created_id' => $this->integer()->notNull()->defaultValue(0)->comment('创建用户'),
            'updated_at' => $this->dateTime()->notNull(),
            'updated_id' => $this->integer()->notNull()->defaultValue(0)->comment('修改用户'),
        ],$tableOptions);

        $time = date('Y-m-d H:i:s');

        // 写入数据
        $this->batchInsert($this->table, [
            'username',
            'auth_key',
            'password_hash',
            'email',
            'name',
            'last_time',
            'last_ip',
            'now_time',
            'now_ip',
            'role',
            'created_at',
            'created_id',
            'updated_at',
            'updated_id',
        ], [
            [
                'super',
                'B2HObkRuJEz6sAGTx-gbSl6VfFsca0ib',
                '$2y$13$Wq/Ird/qloRPzFspMxZkKuDB3LQu6Dj2Z0xdVdmW0mrNnUirRTv7y',
                'super@qq.com',
                '超级管理员',
                $time,
                '127.0.0.1',
                $time,
                '127.0.0.1',
                'administrator',
                $time,
                1,
                $time,
                1
            ],
            [
                'admin',
                'B2HObkRuJEz6sAGTx-gbSl6VfFsca0ib',
                '$2y$13$Wq/Ird/qloRPzFspMxZkKuDB3LQu6Dj2Z0xdVdmW0mrNnUirRTv7y',
                'admin@qq.com',
                '管理员',
                $time,
                '127.0.0.1',
                $time,
                '127.0.0.1',
                'admin',
                $time,
                1,
                $time,
                1
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
