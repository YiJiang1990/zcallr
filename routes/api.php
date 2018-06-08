<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('version', function () {
        $api->post('user/login', 'LoginController@login');
    });
});
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'serializer:array'
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        // 游客可以访问的接口
        // 获取token
        $api->post('user/login', 'AuthorizationsController@store')->name('api.socials.authorizations.store');
        // 创建账号
        $api->post('user/register', 'RegisterController@register')->name('api.register');


        /*---------------------------------------------------分割线---------------------------------------------------*/

        // 需要 token 验证的接口
        $api->group(['middleware' => 'jwt.auth'], function ($api) {
            // 刷新token
            $api->put('authorizations/current', 'AuthorizationsController@update')->name('api.authorizations.update');
            // 删除token
            $api->delete('authorizations/current', 'AuthorizationsController@destroy')->name('api.authorizations.destroy');
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')->name('api.user.show');
        });

    });

});
