<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/15
 * Time: 下午5:23
 */

namespace common\tools;


class GetMacAddr{

    function getMAC() {
        $Tmpa =0;
        @exec("ipconfig /all",$array);
        for($Tmpa;$Tmpa<count($array);$Tmpa++){
            if(eregi("Physical",$array[$Tmpa])){
                $mac=explode(":",$array[$Tmpa]);
                return $mac[1];
            }
        }
    }

}