<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<style>

    body {
        margin: 0 auto;
        margin-top: 48px;
        max-width: 616px;
        padding: 0 16px;
        font-family: 'Roboto', 'Helvetica Neue', sans-serif;
        font-size: 16px;
        line-height: 24px;
        color: rgba(0,0,0,0.87);
    }
    h1, h2, h3 {
        font-family: 'Roboto', 'Helvetica Neue', sans-serif;
        font-weight: 300;
    }
    h1 {
        margin: 24px 0 16px 0;
        padding: 0 0 16px 0;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        font-size: 32px;
        line-height: 36px;
    }
    h2 {
        margin: 24px 0 16px 0;
        padding: 0;
        font-size: 20px;
        line-height: 32px;
        color: rgba(0,0,0,0.54);
    }
    p {
        margin: 0;
        margin-bottom: 16px;
    }
    ol {
        margin: 0;

    }
    ol li {
        margin: 0;
        line-height: 24px;
        padding-left: 12px;
    }
    a {
        color: #039BE5;
        text-decoration: underline;
    }
    a:hover {
        text-decoration: underline;
    }
    code {
        display: inline-block;
        padding: 3px 4px;
        background-color: #ECEFF1;
        border-radius: 3px;
        font-family: 'Roboto Mono',"Liberation Mono",Courier,monospace;
        font-size: 14px;
        line-height: 1;
    }
    .logo {
        display: block;
        text-align: center;
        margin-top: 48px;
        margin-bottom: 24px;
    }
    img {
        width: 220px;
    }
    @media screen and (max-width: 616px) {
        body {
            margin-top: 24px;
        }

        .logo  {
            margin: 0;
        }
    }
</style>
<div class="site-error" style=" margin: 0 auto;width: 600px;margin-top:10% ">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <h2>Why am I seeing this?</h2>
    <p>当Web服务器正在处理您的请求时发生上述错误。
    </p>
    <p>
    如果您认为这是服务器错误，请与我们联系。谢谢您。
    </p>

</div>
