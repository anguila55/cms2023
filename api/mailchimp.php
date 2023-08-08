<?php

use MailchimpTransactional\ApiClient;

require_once($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');

function getUrl($link)
{
    return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $link;
}

function sendMail($mail)
{
    try {
        $mailchimp = new ApiClient();
        $mailchimp->setApiKey('Gfj5Sm2_5BX4VrU4NctlOA');
        return $mailchimp->messages->send(["message" => $mail]);
    } catch (Error $e) {
        return $e->getMessage();
    }
}

function exportActivity($mail){

    try{
        $mailchimp = new ApiClient();
        $mailchimp->setApiKey('Gfj5Sm2_5BX4VrU4NctlOA');
       // $mailchimp->exports->activity();
      //  var_dump($mailchimp->exports->list());die;
      
      return $mailchimp->tags->info(["tag" => $mail]);

    }catch (Error $e){
        return 0;
    }

}

?>