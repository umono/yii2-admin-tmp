<?php
    /**
     * Created by PhpStorm.
     * User: umono
     * Date: 2019/1/29
     * Time: 3:46 PM
     */

    namespace backend\controllers;


    class TestController extends \yii\web\Controller
    {
        public function actionIndex()
        {
            $str = [

                "%D6%D0%BA%E3%C0%F1%D2%B5",
                "%BD%ED%C6%D5%C2%DE%CC%EC",
                "%CD%DF%CA%C7%D6%A3%B0%FC%B0%FC",
                "%C7%D5%C8%F3%BC%D2%BE%D3%D7%A8%D3%AA%B5%EA",
                "%E2%F9%BF%CC%C6%EC%BD%A2%B5%EA",
                "%BF%F1%CA%A8%C6%EC%BD%A2%B5%EA",
                "%C0%CB%C2%FE%CF%E3%E9%BF%C0%F1%C6%EC%BD%A2%B5%EA",
                "%CE%C0%CA%CB%B4%EF%C6%EC%BD%A2%B5%EA",
                "applerabbit%C6%EC%BD%A2%B5%EA",
                "%BD%F0%C9%AB%C4%EA%BB%AA%BC%D2%BE%D3%D7%A8%D3%AA%B5%EA",

                "%B9%E2%B2%CA%C4%EA%BB%AA%C6%EC%BD%A2%B5%EA",

                "%B0%AE%B2%BC%C4%DD%C6%EC%BD%A2%B5%EA",

                "%BB%AA%D2%D7%CC%D8%C6%EC%BD%A2%B5%EA",

                "%B0%AC%BF%C6%D1%B8%BC%D2%BE%D3%D7%A8%D3%AA%B5%EA",

                "%BA%EA%C3%C0%CC%EC%D2%D5%D7%A8%D3%AA%B5%EA",

                "%B8%A3%BD%C7%BD%B3%C6%EC%BD%A2%B5%EA",

                "onefire%CD%F2%BB%F0%D1%A9%BA%BD%D7%A8%C2%F4%B5%EA",

                "%C8%AB%B3%F8%C6%EC%BD%A2%B5%EA",

                "%BA%EA%D3%EE%BC%AA%CF%E9%BC%D2%BE%D3%D7%A8%D3%AA%B5%EA",

                "%C7%C0%D1%DB%C7%F2%C6%EC%BD%A2%B5%EA",

                "%B0%AE%C9%D0%C0%F1%BC%D2%BE%D3%D7%A8%D3%AA%B5%EA",

                "kufire%C6%EC%BD%A2%B5%EA",

                "kufire%C6%EC%BD%A2%B5%EA",

                "%B0%EE%CD%DE%C0%D6%C6%EC%BD%A2%B5%EA",

                "onefire%CD%F2%BB%F0%C6%EC%BD%A2%B5%EA",

                "onefire%CD%F2%BB%F0%C6%EC%BD%A2%B5%EA",

                "%B0%EE%CD%DE%C0%D6%C6%EC%BD%A2%B5%EA",
                "%B9%E2%B2%CA%C4%EA%BB%AA%C6%EC%BD%A2%B5%EA",
                "%B5%DB%D2%F4%C6%EC%BD%A2%B5%EA",
                "%D0%C0%C0%BC%D1%C5%C9%E1%C6%EC%BD%A2%B5%EA",
                "%BE%BA%E2%F9%D1%D5%BD%DB%D7%A8%C2%F4%B5%EA",
                "%C8%E7%C7%DF%BC%D2%BE%D3%C6%EC%BD%A2%B5%EA",
                "%B0%B2%E7%F7%B6%DC%C6%EC%BD%A2%B5%EA",

                "%D0%C0%C0%BC%D1%C5%C9%E1%C6%EC%BD%A2%B5%EA",

                "%E2%F9%BF%CC%C6%EC%BD%A2%B5%EA",

                "%BD%F0%87%D61%BA%C5%C6%EC%BD%A2%B5%EA",

                "pato%C6%EC%BD%A2%B5%EA",

                "%B8%C7%D7%BF%C6%EC%BD%A2%B5%EA",

                "%C3%DC%D3%D1%C8%D5%BC%C7%C6%EC%BD%A2%B5%EA",

                "%C1%B5%B2%AF%C6%EC%BD%A2%B5%EA",

                "langdi%BC%D2%BE%D3%C6%EC%BD%A2%B5%EA",

                "%C4%B2%CA%CF%BC%D2%BE%D3%D7%A8%D3%AA%B5%EA",

                "%D3%C5%C3%C0%C6%E6%BC%D2%BE%D3%D7%A8%D3%AA%B5%EA",

                "%CA%AB%C8%F0%B7%D2%C6%EC%BD%A2%B5%EA",
            ];
            return $this->asJson($str);

        }
    }