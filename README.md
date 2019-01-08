#安装

```

git clone https://git.coding.net/tmoment/yii2-rbac-oatuh2.git
cd yii2-rbac-oatuh2/ && composer install
php ./init 

```

## 修改文件

### 修改 

```php
//路径/vendor/filsh/yii2-oauth2-server/migrations/m140501_075311_add_oauth2_server.php

    //修改前
    public function primaryKey($columns) {
        return 'PRIMARY KEY (' . $this->db->getQueryBuilder()->buildColumns($columns) . ')';
    }
    //修改后
    public function primaryKey($columns = null) {
        return 'PRIMARY KEY (' . $this->db->getQueryBuilder()->buildColumns($columns) . ')';
    }


//路径/vendor/filsh/yii2-oauth2-server/Modele.php
    //修改前
    public function getRequest()
    {
        if(!$this->has('request')) {
            $this->set('request', Request::createFromGlobals());
        }
        return $this->get('request');
    }
    
    public function getResponse()
    {
        if(!$this->has('response')) {
            $this->set('response', new Response());
        }
        return $this->get('response');
    }
    //修改后
    public function getRequest()
    {
        if(!ArrayHelper::keyExists('request', $this->getComponents())) {
            $this->set('request', Request::createFromGlobals());
        }
        return $this->get('request');
    }
    
    public function getResponse()
    {
        if(!ArrayHelper::keyExists('response', $this->getComponents())) {
            $this->set('response', new Response());
        }
        return $this->get('response');
    }
```
## 数据库迁移

```base
//依次执行 
yii migrate --migrationPath=@vendor/filsh/yii2-oauth2-server/migrations
yii migrate --migrationPath=@yii/rbac/migrations
yii migrate ###最后执行
```

** 初始用户 **

super
123123
admin
123123# yii2-admin-tmp
