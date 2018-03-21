<?

/**
 * Ajax
 */

include_once('rsa.php');

$RSA = new RSA;

// тестовая фраза
$source = "RSA это прекрасный инструмент для защиты данных от перехвата третьими лицами!";

echo $RSA->encrypt($source);

?>