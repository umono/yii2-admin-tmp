<?php
/**
 * Created by PhpStorm.
 * User: umono
 * Date: 2018/12/11
 * Time: 4:05 PM
 */

namespace common\components;


class Constant
{
    public $api;
    public $admin;
    public $appid;
    public $secret;
    public $mch_id;
    public $mch_key;

    public function __construct()
    {
        $this->api = getenv('HOST_API');
        $this->admin = getenv('HOST_ADMIN');

        $this->appid = getenv('AppID');
        $this->secret = getenv('AppSecret');

        $this->mch_id = getenv('MCH_ID');
        $this->mch_key = getenv('MCH_KEY');
    }
}