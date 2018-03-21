# Передача через RSA 

Надстройка для веб-приложения, для передачи и прием данных, используя ассиметричное шифрование RSA.

Серверная часть работает на PHP 7.2. Клиентская на JavaScript (jQuery 3.2.1).

Не используется базы данных MySQL. Ключи хранятся в файле формата JSON.

## Принцип работы

1. При первом посещении PHP создает ключ сессии и генерирует пару ключей (публичный и приватный). Ключи сохраняются в файле {session_id}.json

2. Клиентская часть генерирует ключи, испольуя скрипт JSEncrypt ( https://github.com/travist/jsencrypt ). Ключи сохраняются в WebStorage (local).

3. Клиентская часть обменивается публичными ключами. На сервер сохраняется публичный ключ клиента, в ответ приходит публичный ключ сервера. Ключи записываются в файл и в localstorage, соответсвенно.

4. Данные передаются испольуя шифрование RSA.

