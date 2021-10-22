# e-phone
大迈通

```phpregexp
$config = [
    'url' => 'http://syshb.cddmt.cn:9999',
    'appid' => 'appid',
    'uname' => 'uname',
    'password' => 'password',
    'notifyUrl' => 'notifyUrl',
    'sig' => '',
];

$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE2MzQ4ODcwMjYsInVzZXJuYW1lIjoicm9uZ2NodWFuZyJ9.qns105qtdCi5qCn0bSZJCzvdVe77DWkjPOPt2eogxbk';

$seatId = '12345656';

$activePhone = '182******';

$EPhone = new EPhone($config);

$EPhone->bindToken($token);

//登录
if (0) {
    $response = $EPhone->login();
    var_dump($response->getBody(), $response->getMessage(), $response->isSuccess(), $response->getError(), $response->getToken());
}
//打电话
if (0) {
    $toPhone = '177*****';
    $response = $EPhone->phone($activePhone, $toPhone, $seatId);
    var_dump($response->getBody(), $response->getMessage(), $response->isSuccess(), $response->getError(), $response->getTaskId());
    //token过期
    var_dump($response->getCode() == '400');

}
//创建坐席
if (0) {
    $response = $EPhone->createSeat($activePhone, '张三');
    var_dump($response->getBody(), $response->getMessage(), $response->isSuccess(), $response->getError(), $response->getSeatNo());
}
//删除坐席
if (0) {
    $response = $EPhone->deleteSeat($seatId, $activePhone);
    var_dump($response->getBody(), $response->getMessage(), $response->isSuccess(), $response->getError());
}
//坐席状态
if (0) {
    $response = $EPhone->seatStatus($seatId);
    var_dump($response->getBody(), $response->getMessage(), $response->isSuccess(), $response->getError(), $response->getStatus());
}
//重新发送验证码
if (0) {
    $response = $EPhone->captchaResend($activePhone);
    var_dump($response->getBody(), $response->getMessage(), $response->isSuccess(), $response->getError());
}
//移动4加白
if (0) {
    $idCard = '5555222333';
    $name = '张三';
    $response = $EPhone->whiteAppend($activePhone, $idCard, $name);
    var_dump($response->getBody(), $response->getMessage(), $response->isSuccess(), $response->getError());
}
//异步通知
if (0) {
    $data = '{"bindId":"1201_7509_4294967295_20211022061150@callenabler245qh2.huaweicaas.com","callerNum":"18215626530","fwdDstNum":"17716873715","callInTime":"2021-10-22 14:12:20.0","callEndTime":"2021-10-22 14:12:24.0","recordUrl":"http:\/\/106.14.0.130:9005\/record_url_h\/21102206122012030396639.wav"}';
    $response = $EPhone->notify($data);
    var_dump($response->getBindId(),
        $response->getCallerNum(),
        $response->getFwdDstNum(),
        $response->getCallInTime(),
        $response->getCallEndTime(),
        $response->getRecordUrl());
}

```