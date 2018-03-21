<?

/**
 * Ajax 
 */

include_once('rsa.php');

$RSA = new RSA;

$source = $_POST['encryptdata'];

echo $RSA->decrypt($source);

?>