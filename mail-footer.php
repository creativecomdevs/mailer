<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';

$admin_mail = "hello@scalari.io";
$mail2 = new PHPMailer(true);
//From email address and name
$mail2->CharSet = 'utf-8';
$mail2->From = "hello@scalari.io";
$mail2->FromName = "Message from form";
//To address and name

$mail2->addAddress($admin_mail);

//Send HTML or Plain Text email
$mail2->isHTML(true);

$mail2->Subject = "Message from Feedbackform";
$mail2->Body = '
        <p><i>Name</i> : <b>' . $_POST["name"] . '</b></p>
        <p><i>Phone</i> : <b>' . $_POST["phone"] . '</b></p>
        <p><i>Company</i> : <b>' . $_POST["company"] . '</b></p>
        <p><i>E-mail</i> : <b>' . $_POST["email"] . '</b></p>
        <p><i>Position</i> : <b>' . $_POST["position"] . '</b></p>
        <p><i>Message</i> : <b>' . $_POST["textarea"] . '</b></p>
        <br/>
        <sub>Send : ' . date("d-m-Y H:i:s") . '</sub>';
$mail2->AltBody = 'Name : ' . $_POST["name"] . ' | E-mail : ' . $_POST["email"] . ' | Message : ' . $_POST["textarea"];

    sendDataToBitrix($_POST);

try {
    $mail2->send();


    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail2->ErrorInfo}";
}


$mail3 = new PHPMailer(true);
//From email address and name
$mail3->CharSet = 'utf-8';
$mail3->From = $admin_mail;
$mail3->FromName = "Message from footer";
//To address and name

$mail3->addAddress($_POST["email"]);

//Send HTML or Plain Text email
$mail3->isHTML(true);

$mail3->Subject = "Message from Feedbackform";
$mail3->Body = `<p>jsadhasdhl</p>`;
$mail3->AltBody = 'Name : '.$_POST["name"].' | E-mail : '.$_POST["email"].' | Message : '.$_POST["textarea"];

try {
    $mail3->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail3->ErrorInfo}";
}






function sendDataToBitrix($data)
{
    $queryUrl = 'https://abcyarmarka.bitrix24.ru/rest/1/pz4fvr1g1uozpafk/crm.lead.add';
    $queryData = http_build_query([
        'fields' => [
            'TITLE' => 'Лид из формы "CONTACT US" сайта ',
            'ASSIGNED_BY_ID' => 1,
            'NAME' => $data["name"],
            'PHONE' => [
                'n0' => [
                    'VALUE' => $data['phone'],
                    'VALUE_TYPE' => 'WORK',
                ]
            ],
            'EMAIL' => [
                'n0' => [
                    'VALUE' => $data['email'],
                    'VALUE_TYPE' => 'WORK',
                ]
            ],
            'COMMENTS' => $data["textarea"],
        ],
        'params' => [
            'REGISTER_SONET_EVENT' => 'Y',
        ]
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result, 1);
}