<?php
/**
 * Author: luzheng.liu
 * Time: 2019-12-18 23:13
 */

namespace app\index\service;


class RiBaoService {

    public function sendMsg($data) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=abbe9a66-2d91-4430-ac3c-6f5630404042",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}