<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/13
 * Time: 下午3:29
 */

namespace api\controllers;

use Yii;
use yii\web\Controller;

class OauthController extends Controller
{
    protected $_server;
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {

        if (!parent::beforeAction($action)) return false;

        $db = Yii::$app->db;
        $dsn = $db->dsn;
        $username = $db->username;
        $password = $db->password;
        $tablePrefix = $db->tablePrefix;
        $charset = $db->charset;

        //ini_set('display_errors',1);
        //error_reporting(E_ALL);
        $storage = new \OAuth2\Storage\Pdo(
            array('dsn' => $dsn, 'username' => $username, 'password' => $password, 'options' => array('tablePrefix' => $tablePrefix, 'charset' => $charset))
            ,
            array(  //这个添加前缀的数组在开始测试的时候建议不要加进去
                'client_table' => $tablePrefix . 'oauth_clients',
                'access_token_table' => $tablePrefix . 'oauth_access_tokens',
                'refresh_token_table' => $tablePrefix . 'oauth_refresh_tokens',
                'code_table' => $tablePrefix . 'oauth_authorization_codes',
                'user_table' => $tablePrefix . 'oauth_users',
                'jwt_table' => $tablePrefix . 'oauth_jwt',
                'jti_table' => $tablePrefix . 'oauth_jti',
                'scope_table' => $tablePrefix . 'oauth_scopes',
                'public_key_table' => $tablePrefix . 'oauth_public_keys',
            ));
        $server = new \OAuth2\Server($storage, array('enforce_state' => false, 'id_lifetime' => 3600, 'access_lifetime' => 3600));
        $server->addGrantType(new \OAuth2\GrantType\ClientCredentials($storage));
        $server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($storage));
        $server->addGrantType(new \OAuth2\GrantType\RefreshToken($storage, array('always_issue_new_refresh_token' => true, 'unset_refresh_token_after_use' => true)));
        $server->addGrantType(new \OAuth2\GrantType\UserCredentials($storage));
        $this->_server = $server;
        return true;

    }

    // 获取token
    public function actionToken()
    {
        $this->_server->handleTokenRequest(\OAuth2\Request::createFromGlobals())->send();
    }

//验证token的正确性
    public function actionResource()
    {
        if (!$this->_server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            return $this->_server->getResponse()->send();
        }
        echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));
    }

    //获取客户端的授权 并获取 code 再拿这个code 去token 方法里面去再次获取token。
    //记到handleAuthorizeRequest()的第4个参数要写进去

    public function actionAuthorize()
    {
        if (!$this->_server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            exit('您的平台的token没有通过验证！');
        }

        $request = \OAuth2\Request::createFromGlobals();
        $response = new \OAuth2\Response();

        if (!$this->_server->validateAuthorizeRequest($request, $response)) {
            return $response->send();
        }

        if (empty($_POST)) {
            return ' <form method="post">   
			
                                    <ul>
                                    <li>用户名：<input type="text" name="username" value=""></li>
                                    <li>密码：<input type="password" name="passwd" value=""></li>
                                    <li><label>您要授权给testclient平台吗？</label></li>
				<li><input type="submit" name="authorized" value="yes"></li> 
				<li><input type="submit" name="authorized" value="no"></li>
                                    </ul>
                                    </form>';
        }

        $is_authorized = ($_POST['authorized'] === 'yes');
        /*这个里面写业务逻辑*/

        $reques = Yii::$app->request;
        $isOk = false;

        if ($member = Member::findLogin(BaseHelper::encode($reques->post('username')))) {

            if (!empty($reques->post('passwd')) && !empty($member->password_hash) && Yii::$app->security->validatePassword($reques->post('passwd'), $member->password_hash)) {
                $isOk = true;
            } else {
                $isOk = false;
            }
        } else {
            $isOk = false;
        }

        if (!$isOk) {
            exit('您的用户名或密码错误');
        }

        /*这个里面写业务逻辑*/


        $this->_server->handleAuthorizeRequest($request, $response, $is_authorized, $member->id);
        //这个$member->id表示的 是member表里面有的userid(为了以后方便自己查找) 这把token与userid绑定到一起。 通过下面的actionAcc()方法可以看出来。
        if ($is_authorized) {
            if (strpos('#', $response->getHttpHeader('Location')) != false) {
                $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);
            } else {
                $code = $response->getHttpHeader('Location');
            }
            $arr2 = explode('code=', $code);
            $this->jumpurl($arr2[1]);//跳转回去
            //exit("SUCCESS! Authorization Code: $code");
        }


        //
        return $response->send();
    }


    public  function actionAcc(){
        $token = $this->_server->getAccessTokenData(\OAuth2\Request::createFromGlobals());
        echo "User ID associated with this token is {$token['user_id']}";
        print_r($token);
    }
    /**
     * 跳转一个url
     */
    public  function jumpurl($code){
        $request = Yii::$app->request;
        $redirect_uri = $request->get('redirect_uri2');
        header("Location:".$redirect_uri.'?code='.$code);
        exit;
    }

}
