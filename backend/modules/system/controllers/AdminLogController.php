<?php

namespace backend\modules\system\controllers;

use backend\controllers\Controller;
use Yii;

class AdminLogController extends Controller
{
    public function actionTest(){
        return $this->render('test');
    }
}

