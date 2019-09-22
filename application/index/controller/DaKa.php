<?php
/**
 * Author: luzheng.liu
 * Time: 2019-09-22 16:17
 */

namespace app\index\controller;


use app\index\service\DaKaService;
use think\Controller;

class DaKa extends Controller {

    public function daKa() {
        $service = new DaKaService();
        $service->run();
    }

}