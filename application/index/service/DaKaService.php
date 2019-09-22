<?php
/**
 * Author: luzheng.liu
 * Time: 2019-09-22 16:18
 */

namespace app\index\service;


use app\index\model\DakaHoliday;

class DaKaService {


    public $data = [
        'ValidYN'      => 'Y',
        'AppToken'     => 66778899,
        'CardTime'     => '2019-09-17+09:04',
        'Address'      => '潮州路办公室考勤点',
        'AppID'        => 'A|MI+8+SE-9|2.2.4|11584|862860041542790|',
        'StaffID'      => '796996',
        'UserID'       => '11584',
        'Dimension'    => '31.265277',
        'Longitude'    => '121.414143',
        'MobileID'     => '862860041542790',
        'CardRemarkSZ' => ''
    ];
//
//    public $dakaInfo = [
//        "OrderBy"   => "",
//        "AppToken"  => "66778899",
//        'StaffID'   => '796996',
//        'pageSize'  => '31',
//        'CardFrom'  => '',
//        'DeptID'    => '',
//        'EndDate'   => '2019-09-11',
//        'pageNum'   => '1',
//        "CompanyId" => 10,
//        'AppID'     => 'A|MI+8+SE-9|2.2.4|11584|862860041542790|192.168.31.25',
//        'BeginDate' => '2019-09-11',
//        'UserID'    => '11584',
//        'LangID'    => '1',
//    ];


    public function __construct() {
        $date = date('Y-m-d');
        $hour = date('H');
        list($year, $month, $day) = explode('-', $date);
        if ($hour >= 6 && $hour <= 12) {
            $min = $day +2;
            $this->data['CardTime'] = $date . '+09:' . $min;
            $makeTime = "$date 09:$min";

        } else {
            $min = $day ;
            $this->data['CardTime'] = $date . '+19:' . $min;
            $makeTime = "$date 19:$min";
        }
        $cronTime = date('Y-m-d H:i');
        if ($cronTime != $makeTime) {
            var_dump($cronTime, $makeTime);
            die();
        }
        var_dump("打卡时间" . date('Y-m-d H:i:s'));
    }

    public function run() {
        list($hour, $min, $sec) = explode(':', date('H:i:s'));
        $date = date('Y-m-d');
        list($year, $month, $day) = explode('-', $date);
        $isHoliday = $this->getHoliday($year, (int)$month);
        if ($isHoliday) {
            var_dump($date . "这天是假期不打卡，退出");
            die();
        }
        var_dump($date . "正常上班");
        if ($hour >= 6 && $hour <= 12) {
            var_dump($date . "上班，打卡");
        } else {
            var_dump($date . "下班，打卡");
        }
        $this->data['AppID'] .=  '192.168.1.'.random_int(1, 200);
        $this->curlDaka($this->data);


    }

    //获取当月假期
    public function getHoliday($year, $month) {
        $holidayModel = new DakaHoliday();
        $holidayListByDb = $holidayModel::where(['request_date' => date('Y-m')])->column('holiday');
        if (!empty($holidayListByDb)) {
            if (in_array(date('Y-m-d'), $holidayListByDb)) {
                var_dump(date('Y-m-d') . "这天是假期不打卡，退出");
                return true;
            }
            var_dump(date('Y-m-d') . "正常上班");
            return false;
        }
        $holidayFlag = false;
        $returnInfo = file_get_contents("http://v.juhe.cn/calendar/month?year-month={$year}-{$month}&key=3384938f081a4a6338dcc11fdb66252f");
        $data = json_decode($returnInfo, true);
        if ($data['error_code'] !== 0) {
            var_dump("请求假期接口失败--{$returnInfo}");
            die();
        }
        $insertData = [];
        $holidayList = array_column($data['result']['data']['holiday_array'], 'list');
        $holidayList = array_merge(...$holidayList);
        foreach ($holidayList as $k => $v) {
            if ($v['status'] != 1) {
                continue;
            }
            $date = date('Y-m-d', strtotime($v['date']));
            if ($date === date('Y-m-d')) {
                $holidayFlag = true;
            }
            $insertData[] = [
                'holiday'      => date('Y-m-d', strtotime($v['date'])),
                'request_date' => date('Y-m')
            ];
        }
        $holidayModel->insertAll($insertData, true);

        return $holidayFlag;
    }

    function curlDaka($data) {
        $data = http_build_query($data);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_PORT           => "1001",
            CURLOPT_URL            => "http://hr.baodao.com.cn:1001/AppWebService/GhrApp.asmx/InsertStaffCardRecord",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err."\n";
            die();
        }
        echo $response."\n";

    }

    public function curlDakaInfo($data) {

        $data = http_build_query($data);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_PORT           => "1001",
            CURLOPT_URL            => "http://hr.baodao.com.cn:1001/AppWebService/GhrApp.asmx/GetStaffCardRecordPeyDay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_HTTPHEADER     => [
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Type: application/x-www-form-urlencoded",
                "Host: hr.baodao.com.cn:1001",
                "cache-control: no-cache"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err . "\n";
            die();
        }
        return $response;

    }
}