<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use GuzzleHttp\Client;

use App\Model\UserModel;
class LoginController extends Controller
{
    
    /*
用户注册
 */
    public function reg(Request $request)
    {
        // echo '<pre>';print_r($request->input());echo'</pre>';

        $pass1 = $request->input('pass1');
        $pass2 = $request->input('pass2');
        if ($pass1 != $pass2) {
            die('两次输入密码不一样');
        }
        $password = password_hash($pass1,PASSWORD_BCRYPT);

        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => $password,
            'mobile' => $request->input('mobile'),
            'last_login' => time(),
            'last_ip' => $_SERVER['REMOTE_ADDR'],
        ];
        // dd($data);
        $uid = UserModel::insertGetId($data);
        var_dump($uid);
       
    }

    public function login(Request $request)
    {
    	// $name = $request->input('name');
    	// $pass = $request->input('pass');

    	// echo "pass: ",$pass;\
    	// 
    	$name = $request->input('name');
        $pass1 = $request->input('pass1');
        $u = UserModel::where(['name'=>$name])->first();
        if($u){
            //验证密码
            if( password_verify($pass1,$u->password) ){
                // 登录成功
                //echo '登录成功';
                //生成token
                $token = Str::random(32);
                $response = [
                    'errno' => 0,
                    'msg'   => 'ok',
                    'data'  => [
                        'token' => $token
                    ]
                ];
            }else{
                $response = [
                    'errno' => 400003,
                    'msg'   => '密码不正确'
                ];
            }
        }else{
            $response = [
                'errno' => 400004,
                'msg'   => '用户不存在'
            ];
        }
        return $response;
    }
    // public function userList()
    // {
    //     echo "1111";
    // }

    // public function postman1()
    // {
    //         echo "123";
    // }
    public function userList()
    {
        $data = [
            'name' => '123',
            'tel'     => '17603598188',
        ];
        echo '<pre>';print_r($data);echo'</pre>';

    }
    public function qm(){

        $data="hello";
        $key="bobo";

        //生成签名
        $signature=md5($data.$key);

        echo "待发送的数据：". $data;echo '</br>';
        echo "签名：". $signature;echo '</br>';

        //发送数据
        $url = "http://1905passport.com/login/yq?data=".$data .'&signature='.$signature;
//        $url = "http://1905passport.com/yq?data=".$data .'&signature='.$signature;
        echo $url;echo '<hr>';

        $response = file_get_contents($url);
        echo $response;

        
    }
    //   非对称加密
    public function encrypt(){
        $data="123";
        echo "$data";
//        使用私钥非对称加密
//        openssl_get_privatekey(); 获取私钥
        $path=storage_path("keys/priv.key2");
        $priv_key = openssl_pkey_get_private("file://".$path);
//        $priv_key=file_get_contents(storage_path("keys/priv_key2"));
        openssl_private_encrypt($data,$encrypt_data,$priv_key,OPENSSL_PKCS1_PADDING);
        var_dump($encrypt_data);
//        将密文base64
        $base64_str=base64_encode($encrypt_data);
        echo $base64_str;echo '</br>';
        $url_encode_str = urlencode($base64_str);
        echo '$url_encode_str : '.$url_encode_str;echo '</br>';
        $url="http://1905passport.com/decrypt?data=".$url_encode_str;
        echo $url;echo'</br>';
        $response=file_get_contents($url);
        echo $response;
    }
    //    对称加密
    public function encrypt2(){
//        echo print_r($_GET);
        $key="bobo";
        $data="hello word";
        $method="AES-256-CBC";
        $iv="qwertsdffffghasd";
        $enc_data=openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
        echo "加密密文：".$enc_data;echo "</br>";
//        将base64
        $base64_str=base64_encode($enc_data);
        echo "base64后的密文：".$base64_str;echo "</br>";
        $url_encode_str = urlencode($base64_str);
        echo '$url_encode_str : '.$url_encode_str;echo '</br>';
//        发送加密数据
        $url="http://1905passport.com/decrypt2?data=".$url_encode_str;
        echo $url;
        $response=file_get_contents($url);
        echo $response;
    }
   }
