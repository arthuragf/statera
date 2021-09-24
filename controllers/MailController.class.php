<?php
namespace statera\controllers;

use statera\libraries\PHPMailer\src\PHPMailer;
use statera\libraries\PHPMailer\src\Exception;

class MailController {
    public PHPMailer $clsPHPMailer;
    public $sErrorMessage = '';

    public function __construct(array $aConfig) {
        $this->clsPHPMailer = new PHPMailer(true);
        if ($aConfig['bIsSmtp']) {
            $this->clsPHPMailer->isSMTP();
        }
        $this->clsPHPMailer->SMTPAuth = $aConfig['nRequireAuth'] ? true : false;
        $this->clsPHPMailer->Username = $aConfig['sUserName'];
        $this->clsPHPMailer->Password = $aConfig['sMailPass'];
        $this->clsPHPMailer->SMTPSecure = $aConfig['sSmtpSecure'];
        $this->clsPHPMailer->Host = $aConfig['sHostSmtp'];
        $this->clsPHPMailer->Port = $aConfig['nPortSmtp'];
        $this->clsPHPMailer->isHTML($aConfig['nIsHtml'] ? true : false);
        $this->clsPHPMailer->setFrom(
            $aConfig['sUserName']
            , ($aConfig['sFromName']) ?? $aConfig['sUserName']
        );
    }

    public function sendMail(array $aParams): bool {
        
            $this->clsPHPMailer->addAddress(
                $aParams['aRecipient']['sRecipientEmail']
                , $aParams['aRecipient']['sRecipientName'] ?? ''
            );
            $this->clsPHPMailer->Subject = $aParams['sSubject'];
            $this->clsPHPMailer->Body    = $aParams['sBody'];
            $this->clsPHPMailer->AltBody = !empty($aParams['sAltBody']) 
                ? $aParams['sAltBody'] 
                : '';
            return $this->clsPHPMailer->send();
    }
}