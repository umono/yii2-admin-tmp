<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <input type="button" value="tets" id="post"/>
            <form class="layui-form layui-form-pane">
                <input type="text" name="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                <input type="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input">
                <select name="interest">
                    <option value="">---</option>
                    <option value="0">000</option>
                    <option value="1" selected="selected">111</option>
                    <option value="2">222</option>
                    <option value="3">333</option>
                    <option value="4">444</option>
                </select>
                <!--//title= amdin & password= admin888 & interest= 4   输出字符串-->
                <!-- [{…}, {…}, {…}] 输出数组 对象 -->
                <input type="radio" name="sex" value="0" title="男" checked="checked">
                <input type="radio" name="sex" value="1" title="女">
                <!-- title=&password= & interest=1 & sex=0  -->
                <input type="checkbox" name="like1[write]" lay-skin="primary" title="写作" checked="">
                <input type="checkbox" name="like1[read]" lay-skin="primary" title="阅读">
                <input type="checkbox" name="like1[game]" lay-skin="primary" title="游戏" disabled="">
                <!---  title=&password=&interest=1&sex=0&like1%5Bwrite%5D=on&like1%5Bread%5D=on  -->
            </form>
        </div>
    </div>
</div>
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://cdn.bootcss.com/crypto-js/3.1.9/crypto-js.js"></script>
<script>

    const AES_KEY = "qq3217834abcdefg"; //16位
    const AES_IV = "1234567890123456";  //16位

    //加密
    function aes_encrypt(plainText) {
        var encrypted = CryptoJS.AES.encrypt(plainText, CryptoJS.enc.Utf8.parse(AES_KEY), {iv:  CryptoJS.enc.Utf8.parse(AES_IV)});
        return CryptoJS.enc.Base64.stringify(encrypted.ciphertext);
    }
    //解密
    function aes_decrypt(ciphertext) {
        var decrypted = CryptoJS.AES.decrypt(ciphertext, CryptoJS.enc.Utf8.parse(AES_KEY), {iv: CryptoJS.enc.Utf8.parse(AES_IV)});
        return decrypted.toString(CryptoJS.enc.Utf8);
    }


    $("#post").click(function () {
        console.log("================");
        console.log($("form").serialize()); //输出字符串
        console.log($("form").serializeArray()); //输出数组
        console.log("================");

        var data = $("form").serialize();
        encrypt_data = aes_encrypt(data);
        var url ='http://api.shop-admin.com/v1/test';
        $.ajax({
            type: 'POST',
            url: url,//发送请求
            dataType : "json",
            data:{id:encrypt_data},
            beforeSend: function(xhr) {
                console.log(xhr)
                console.log('发送前')
            },
            success: function(result) {
                console.log("================");
                console.log(result)
                var dd = aes_decrypt(result);
                console.log(dd)
                console.log("================");
                var jj = JSON.parse(dd)
                console.log(jj)
            },
            error:function () {
            }
        });
    })
    data = $("form").serialize();
    encrypt_data = aes_encrypt(data);
    console.log(encrypt_data);
    decrypt_data = aes_decrypt(encrypt_data);
    console.log(decrypt_data);
</script>
