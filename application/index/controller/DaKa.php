<?php
/**
 * Author: luzheng.liu
 * Time: 2019-09-22 16:17
 */

namespace app\index\controller;


use app\index\service\DaKaService;
use app\index\service\RiBaoService;
use think\Controller;

class DaKa extends Controller {

    public function daKa() {
        $service = new DaKaService();
        $service->run();
    }

    public function ribao() {

        $data = [
            'msgtype' => 'text',
            'text'    => [
                'content' => '汇报日报了各位大佬(已写的请忽略)'
            ]
        ];
        $date = date('Y-m-d');
        var_dump($date);
        $hm = date('H:i');
        $w = date('w', strtotime($date));
        if($w==6 || $w == 0){
            var_dump($date . '周末，跳过');
            die();
        }
        if ($hm !== '22:30') {
            var_dump('不是期望时间不提示');
            die();
        }
        $riBao = new RiBaoService();
        $riBao->sendMsg($data);

    }

}