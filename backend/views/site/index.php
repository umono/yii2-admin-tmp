<style>
    body {
        margin: 0 auto;
        margin-top: 48px;
        max-width: 616px;
        padding: 0 16px;
        background: white !important;
        font-family: 'Roboto', 'Helvetica Neue', sans-serif;
        font-size: 16px;
        line-height: 24px;
        color: rgba(0, 0, 0, 0.87) !important;
    }

    h1, h2, h3 {
        font-family: 'Roboto', 'Helvetica Neue', sans-serif;
        font-weight: 300;
    }

    h1 {
        margin: 24px 0 16px 0;
        padding: 0 0 16px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        font-size: 32px;
        line-height: 36px;
    }

    h2 {
        margin: 24px 0 16px 0;
        padding: 0;
        font-size: 20px;
        line-height: 32px;
        color: rgba(0, 0, 0, 0.54);
    }

    p {
        margin: 0;
        margin-bottom: 16px;
        font-family: 'Roboto Mono', "Liberation Mono", Courier, monospace;
        color: #999;
        font-size: 13px;
        font-weight: 300;
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
        display: inline-block !important;
        padding: 3px 4px !important;
        background-color: #ECEFF1 !important;
        border-radius: 3px !important;
        font-family: 'Roboto Mono', "Liberation Mono", Courier, monospace !important;
        font-size: 14px !important;
        line-height: 1 !important;
        color: #1c1c1d;
    }

    .logo {
        display: block;
        text-align: center;
        margin-top: 48px;
        margin-bottom: 24px;
    }

    .img-box {
        float: left;
        margin-right: 16px;
        width: 50px;
        height: 50px;
    }

    .img-txt {
        height: 50px;
        font-size: 13px;
    }

    .user-info {
        height: 50px;
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    img {
        width: 50px;
        height: 50px;
        border-radius: 80px;
        object-fit: cover;
    }

    @media screen and (max-width: 616px) {
        body {
            margin-top: 24px;
        }

        .logo {
            margin: 0;
        }
    }
</style>
<div class="site-error" style=" margin: 0 auto;width: 600px;margin-top:10% ">
    <h3>
        Hi , <?= $model->username ?>
    </h3>
    <h1>欢迎登录后台管理系统</h1>
    <div class="user-info">
        <div class="img-box">
            <img src="<?= $model->avatar ? $model->avatar : '/img/avatar.png' ?>" alt="">
        </div>
        <div class="img-txt">
            <div>
                <?= $model->username ?></div>
            <div>
                <?= $model->name ?></div>
        </div>
    </div>
    <p>
        上次登录IP：<code><?= $model->last_ip ?></code> 于 <?= $model->last_time ?>
    </p>
    <p>
        本次登录IP：<code><?= $model->now_ip ?></code> 于 <?= $model->now_time ?>
    </p>

</div>
