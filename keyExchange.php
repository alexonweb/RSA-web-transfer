<?php

/**
 * Обмен публичными ключами клиента и сервера 
 * используя AJAX
 */

include_once("rsa.php");

$RSA = new RSA;

$clientPubKey = $_POST['clientPublicKey'];

if ( $clientPubKey ) {

    $RSA->setClientPubKey($clientPubKey);

    echo $RSA->getServerPubKey() ;

    die();

}

?>