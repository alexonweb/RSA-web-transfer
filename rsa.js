/**
 * RSAtransfer 1.0
 * https://github.com/alexonweb/RSA-web-transfer
 * 
 * 
 */

var RSAtransfer = function () {

    this.client_public_key      = null;
    this.client_private_key     = null;
    this.server_public_key      = null;


    this.client_public_key = localStorage.getItem("client_public_key");

    if ( !this.client_public_key ) {

        this.generateKeys();

    } else {

        // ключ есть, подгружаем
        this.client_public_key   = localStorage.getItem("client_public_key");

        this.client_private_key  = localStorage.getItem("client_private_key");

    }


    var self = this;

    waitForClientPublicKey = setInterval( function () {

        self.keyExchange();

        self.server_public_key = localStorage.getItem("server_public_key");

    }, 500);


};

// Метод генерирует ключи
RSAtransfer.prototype.generateKeys = function() {

    var sKeySize = "2048";

    var keySize = parseInt(sKeySize);

    var crypt = new JSEncrypt( {default_key_size: keySize} );

    // Асинхронный метод!
    crypt.getKey( function() {

        this.client_private_key = crypt.getPrivateKey();

        localStorage.setItem("client_private_key", this.client_private_key);
    
        this.client_public_key = crypt.getPublicKey();
    
        localStorage.setItem("client_public_key", this.client_public_key);

    } );

};

// Метод нужен, чтобы успеть сгенерировать публичный ключ клиента, 
// до того как он будет отправлен на сервер
RSAtransfer.prototype.keyExchange = function() {

    this.client_public_key = localStorage.getItem("client_public_key");

    if ( this.client_public_key ) {
        
        clearInterval(waitForClientPublicKey);

        // 
        $.post("keyExchange.php", {clientPublicKey: this.client_public_key}, function(data) {

            if (data) {
                localStorage.setItem("server_public_key", data);
            }

        });

    }

};
