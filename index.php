<?

// RSA формируем ключи
include_once('rsa.php');

$RSA = new RSA;

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <title>RSA test page</title>
    <meta charset="UTF-8">

    <!-- jQuery 3.2.1 -->
    <script src="vendor/javascript/jquery/jquery-3.2.1.min.js"></script>
    <!-- JSEncrypt -->
    <script src="vendor/javascript/JSEncrypt/bin/jsencrypt.min.js"></script>

    <!-- RSA-web-transfer -->
    <script src="rsa.js"></script>

    <!-- RSA demo script -->
    <script>
    $(document).ready(function() {

        var rsatransfer = new RSAtransfer();


        $("#dataAccept").click(function() {

            $.get('test_getting_data.php').done( function(data) {

                $("#dataAcceptResult").append("<strong>Зашифрованный текст, полученный клиентом:</strong> <br/>");
                $("#dataAcceptResult").append(data + "<br/>");
                $("#dataAcceptResult").append("<strong>Расшифрованный текст клиентом:</strong> <br/>");

                var crypt = new JSEncrypt();

                crypt.setPrivateKey(rsatransfer.client_private_key);

                var result = crypt.decrypt(data)

                $("#dataAcceptResult").append("<em>" + result + "</em>" + "<br/>");

            });

        });


        $("#dataSend").click( function () {

            var data = $("#dataText").val();

            $("#dataSendResult").append("<strong>Зашифрованный текст перед отправкой</strong>: <br/>");

            var crypt = new JSEncrypt();

            crypt.setPublicKey(rsatransfer.server_public_key);

            var result = crypt.encrypt(data)    

            $("#dataSendResult").append("<em>" + result + "</em>" + "<br/>");

            $.post('test_sending_data.php', {encryptdata: result}).done( function(data) {
                
                $("#dataSendResult").append("<strong>Расшированный текст сервером:</strong> <br>");

                $("#dataSendResult").append("<em>" + data + "</em>" + "<br>");

            });

        });
    });
    </script>

</head>

<body>

    <h1>RSA web transfer тестовая страница</h1>

    <h2>Прием данных с сервера</h2>

    <button id="dataAccept">Тест</button>

    <div id="dataAcceptResult"></div>

    <!-- -->

    <h2>Передача данных на сервер</h2>

    <textarea id="dataText" rows="6" cols="50">RSA это прекрасный инструмент для отправки данных на сервер и защиты от перехвата третьими лицами</textarea>
    <br />
    <button id="dataSend">Тест</button>

    <div id="dataSendResult"></div>

</body>
</html>