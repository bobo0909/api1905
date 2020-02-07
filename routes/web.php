<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/info',function(){
    phpinfo();
});


Route::get('/test/pay','TestController@alipay');
Route::get('/test/alipay/return','Alipay\PayController@aliReturn');
Route::post('/test/alipay/notify','Alipay\PayController@notify');

//登录注册
Route::get('/test/reg','TestController@reg');
	
Route::get('/test/postman1','TestController@postman1s');


Route::post('/api/usr/reg','Api\LoginController@reg');
Route::post('/api/usr/login','Api\LoginController@login');
Route::get('login/list','Api\LoginController@userList')->middleware('login');


Route::get('/caesar','Kaoshi\KaoshiController@caesar');//加密
Route::get('/jcaesar','Kaoshi\KaoshiController@jcaesar');//解密
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/list','Api\LoginController@userList')->middleware('checktoken','login');
Route::get('/login/qm','Api\LoginController@qm');