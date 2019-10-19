<?php

namespace App\Tool\SMS;

use App\Models\M3Result;

class SendTemplateSMS
{
  //主帐号
  private $accountSid='8aaf07086d62068d016d91403a341bfd';

  //主帐号Token  c7cf5b499ced4e68a764925bde153127    c7a1c3563b1347f4adbe0734f981dac1
  private $accountToken='c7cf5b499ced4e68a764925bde153127';

  //应用Id
  private $appId='8aaf07086d62068d016d91403a821c03';

  //请求地址，格式如下，不需要写https://
  private $serverIP='app.cloopen.com';

  //请求端口
  private $serverPort='8883';

  //REST版本号
  private $softVersion='2013-12-26';

  /**
    * 发送模板短信
    * @param to 手机号码集合,用英文逗号分开
    * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
    * @param $tempId 模板Id
    */
  public function sendTemplateSMS($to,$datas,$tempId)
  {
       $m3_result = new M3Result;

       // 初始化REST SDK
       $rest = new CCPRestSDK($this->serverIP,$this->serverPort,$this->softVersion);
       $rest->setAccount($this->accountSid,$this->accountToken);
       $rest->setAppId($this->appId);

       // 发送模板短信
      //  echo "Sending TemplateSMS to $to <br/>";
       $result = $rest->sendTemplateSMS($to,$datas,$tempId);
       if($result == NULL ) {
           $m3_result->status = 3;
           $m3_result->message = 'result error!';
       }
       if($result->statusCode != 0) {
           $m3_result->status = $result->statusCode;
//           echo $result->statusCode."<br/>";
           $m3_result->message = $result->statusMsg;
//           echo $result->statusMsg."<br/>";
       }else{
           $m3_result->status = 0;
           $m3_result->message = '发送成功';
           $smsmessage=$result->TemplateSMS;
//           echo $smsmessage->dateCreated;
//           echo $smsmessage->smsMessageSid;
       }

       return $m3_result;
  }
}

//sendTemplateSMS("18576437523", array(1234, 5), 1);
