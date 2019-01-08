<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    private $table = '{{%user}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "用户表"';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->unique()->comment('用户名'),
            'nickname' => $this->string(50)->comment('昵称'),
            'auth_key' => $this->string(32)->comment('权限token，登录TOKEN'),
            'password_hash' => $this->string()->notNull()->comment('密码'),
            'password_reset_token' => $this->string(50)->unique()->comment('重置密码token'),
            'email' => $this->string()->notNull()->unique()->comment('邮箱'),
            'age' => $this->integer(10)->defaultValue(0)->comment('年龄'),
            'sex' => $this->string(10)->defaultValue('男')->comment('性别'),
            'member_type' => $this->integer(11)->defaultValue(0)->comment('会员类型'),
            'phone' => $this->string(11)->defaultValue('')->comment('手机号码'),
            'access_token'=>$this->string(50)->unique()->notNull()->defaultValue('')->comment('RESTFUL请求TOKEN'),
            'allowance' => $this->integer(10)->unsigned()->notNull()->defaultValue(0)->comment('restful剩余的允许ing求数'),
            'allowance_updated_at' => $this->integer(10)->unsigned()->notNull()->defaultValue(0)->comment('restful请求的UNIX时间戳数'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
        //写入数据
        $time = date('Y-m-d H:i:s');

        $this->batchInsert($this->table, [
            'username',
            'nickname',
            'auth_key',
            'password_hash',
            'email',
            'created_at',
            'updated_at'
        ], [
        [
        'test',
        '测试用户',
        'pMrkIhOefENMgSPgUPKK1Q6v90jyRrVg',
        '$2y$13$Cixn5c4xBcjm4u6wVsqf3.WfgvmtSlLgqQkfLzOz.orN22EJuUXve',
        'test@test.com',
        $time,
        $time,
        ],
        ]);
    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}
