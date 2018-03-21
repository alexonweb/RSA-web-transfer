<?php

/** 
 * RSA web transfer 1.0
 * https://github.com/alexonweb/RSA-web-transfer
 * 
 * 
 * 
 * 
 */



class RSA
{

    // Папка где хранятся ключи RSA 
    // @todo вынести в отдельный файл
    private $_DIR_RSA = "cache" . DIRECTORY_SEPARATOR . "rsa";

    // Конфигурация RSA шифрования
    // @todo вынести в отдельный файл, подключить к JavaScript
    private $config = array(
        "digest_alg" => "sha2",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    // Содержание файла ключей, генериурется в методе sessionFile()
    private $rsa = null;


    /**
     * Конструктор 
     * Инициализируем сессию, опредяем файл сессии
     */
    public function __construct() 
    {

        if ( !isset ($_SESSION) ) {

            session_start();

            // Файл сессии
            $this->_FILE_RSA = $this->_DIR_RSA . DIRECTORY_SEPARATOR . session_id() . ".json";

            $this->sessionFile();

        } 

    }


    /**
     * Метод создает файл с ключами сессии
     * 
     */
    private function sessionFile()
    {

        if ( file_exists( $this->_FILE_RSA ) ) {

            $this->rsa = file_get_contents($this->_FILE_RSA);

            $this->rsa = json_decode($this->rsa, true);

        } else {

            $this->rsa = $this->keyGeneration();

            file_put_contents($this->_FILE_RSA, json_encode($this->rsa) );

        }

    }


    /**
     *  Метод генерирует ключи RSA 
     *
     */
    private function keyGeneration() 
    {

        $res = openssl_pkey_new($this->config);

        // Получаем приватный ключ
        openssl_pkey_export($res, $privatekey);

        // Получаем публичный ключ
        $publickey = openssl_pkey_get_details($res);

        $publickey = $publickey["key"];

        $rsa['server_public_key'] =  $publickey;

        $rsa['server_private_key'] = $privatekey;

        // Клиентский публичный ключ добавляется во время обмена ключами
        $rsa['client_public_key'] = '';

        return $rsa;

    }


    /**
     *  Метод возвращает публичный ключ сервера
     */
    public function getServerPubKey() 
    {

        return $this->rsa['server_public_key'];

    }

     /**
     *  Метод возвращает публичный ключ сервера
     */
    private function getServerPrivKey() 
    {

        return $this->rsa['server_private_key'];

    }

    /**
     *  Метод возвращает публичный ключ клиента
     */
    public function getClientPubKey() 
    {

        // @todo что если ключ не установлен
        return $this->rsa['client_public_key'];

    }

    /**
     * Метод добавляет публичный ключ клинета в файл ключей
     * возвращает boolen = true в случае удачи
     */
    public function setClientPubKey($client_public_key = null)
    {

        $this->rsa['client_public_key'] = $client_public_key;

        if ( file_put_contents($this->_FILE_RSA, json_encode($this->rsa) ) ) {

            return true;

        }

    }

    /**
     * Метод для шифрования
     * возвращает зашифрованный текст
     * 
     */
    public function encrypt($source)
    {

        $pub_key = $this->getClientPubKey();
    
        openssl_public_encrypt($source, $crypttext, $pub_key);

        return base64_encode($crypttext);

    }

    /**
     * Метод для расшифровки
     * возвращает расщифрованный текст
     * 
     */
    public function decrypt($source)
    {

        $source = base64_decode($source);

        $priv_key = $this->getServerPrivKey();

        openssl_private_decrypt($source, $decrypted, $priv_key);

        return $decrypted;

    }




     // @todo добавить метод logout - session_destroy() и удаление файла ключей

}

?>