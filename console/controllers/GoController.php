<?php

    namespace console\controllers;

    use api\modules\v1\models\user\Member;
    use yii\console\Controller;

    /**
     * Created by PhpStorm.
     * User: umono
     * Date: 2020/3/9
     * Time: 3:25 PM
     */
    class GoController extends Controller
    {
        public function actionT()
        {
            $o = Member::findOne(1);
            var_dump($o);
        }
    }