<?php

use yii\db\Migration;

/**
 * Handles the creation of table `insert_rbac`.
 */
class m180606_010852_create_insert_rbac_table extends Migration
{
    private $table = '{{%auth_item}}';

    private $itemTable = '{{%auth_item_child}}';

    private $ruleTable = '{{%auth_rule}}';

    private $assignmentTable = '{{%auth_assignment}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = time();

        // 第一步写入权限
        $this->batchInsert($this->table, [
            'name',
            'type',
            'description',
            'created_at',
            'updated_at'
        ], [
            //角色
            ['admin', 1, '管理员', $time, $time],
            ['administrator', 1, '超级管理员', $time, $time],
            ['rule-power-admin', 1, '权限角色管理员', $time, $time],
            ['operator', 1, '电脑操作员', $time, $time],
            //权限
            ['role/power/create-power',2,'权限管理 - 添加权限',$time,$time],
            ['role/power/create-role',2,'权限管理 - 添加角色',$time,$time],
            ['role/power/del-role-power',2,'权限管理 - 删除角色权限',$time,$time],
            ['role/power/delete',2,'权限管理 - 删除权限/角色',$time,$time],
            ['role/power/index',2,'权限管理 - 权限列表',$time,$time],
            ['role/power/power-data',2,'权限管理 - Ajax获取权限数据',$time,$time],
            ['role/power/role',2,'权限管理 - 角色列表',$time,$time],
            ['role/power/role-data',2,'权限管理 - Ajax获取角色数据',$time,$time],
            ['role/power/role-power',2,'权限管理 - 添加角色权限',$time,$time],
            ['role/power/role-powers',2,'权限管理 - 角色权限',$time,$time],
            ['role/power/update',2,'权限管理 - 更新权限/角色',$time,$time],
            ['role/power/view',2,'权限管理 - 权限/角色详情',$time,$time],
            //--
            ['system/user/create', 2, '管理员用户 - 创建管理员', $time, $time],
            ['system/user/update', 2, '管理员用户 - 更新管理员', $time, $time],
            ['system/user/delete', 2, '管理员用户 - 删除管理员', $time, $time],
            ['system/user/index', 2, '管理员用户 - 列表', $time, $time],
            ['system/user/view', 2, '管理员用户 - 个人详情', $time, $time],
            ['system/user/user-data', 2, '管理员用户 - Ajax获取数据', $time, $time],
            ['system/user/me', 2, '管理员用户 - 更改个人信息', $time, $time],
            ['system/user/reset-password', 2, '管理员用户 - 更改密码', $time, $time],

            ['system/log/index',2,'操作日志 - 页面',$time,$time],
            ['system/log/log-data',2,'操作日志 - Ajax获取数据',$time,$time],

            ['system/member/index',2,'网站用户 - 页面',$time,$time],
            ['system/member/get-data',2,'网站用户 - Ajax获取数据',$time,$time],
            ['system/member/create',2,'网站用户 - 创建用户',$time,$time],
            ['system/member/delete',2,'网站用户 - 删除用户',$time,$time],
            ['system/member/view',2,'网站用户 - 用户详情',$time,$time],
        ]);

        // 管理员信息
        $admin = [
            'role/power/create-power',
            'role/power/create-role',
            'role/power/del-role-power',
            'role/power/delete',
            'role/power/index',
            'role/power/power-data',
            'role/power/role',
            'role/power/role-data',
            'role/power/role-power',
            'role/power/role-powers',
            'role/power/update',
            'role/power/view',
            //--
            'system/user/create',
            'system/user/update',
            'system/user/delete',
            'system/user/index',
            'system/user/view',
            'system/user/user-data',
            'system/user/me',
            'system/user/reset-password',

            'system/log/index',
            'system/log/log-data',
            //--
            'system/member/index',
            'system/member/get-data',
            'system/member/create',
            'system/member/delete',
            'system/member/view',
        ];

        // 第二步写入超级管理员的权限
        $all = (new \yii\db\Query())->from($this->table)->select('name')->where(['type' => 2])->all();
        if ($all) {
            $insert = [];
            foreach ($all as $value) {
                $insert[] = ['administrator', $value['name']];
                if (!in_array($value['name'], $admin)) {
                    $insert[] = ['admin', $value['name']];
                }
            }

            $this->batchInsert($this->itemTable, ['parent', 'child'], $insert);
        }

        // 第四步写入分配信息
        $this->batchInsert($this->assignmentTable, [
            'item_name',
            'user_id',
            'created_at'
        ], [
            ['administrator', 1, $time],
            ['admin', 2, $time]
        ]);
    }

    public function safeDown()
    {
        $this->delete($this->table);
        return false;
    }

}
