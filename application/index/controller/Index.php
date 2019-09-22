<?php
namespace app\index\controller;

use think\Db;
use think\Validate;

class Index
{
    public function index()
    {

        return 'index';
    }


    public function chatInfo() {

    }


    public function sendChatMsg() {
        $params = input();
        $rules = [
            'userId' => 'require',
            'otherUserId' => 'require',
            'content' => 'require'
        ];

        new Validate($params);
    }

    public function chatList() {

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }


    public function daka() {


    }
}
