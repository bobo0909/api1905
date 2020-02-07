<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
   }
