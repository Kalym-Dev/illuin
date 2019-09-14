<?php
require_once 'lib/Redirect_503.php'; //полключение трейта с функцией редиректа на страницу 503 ошибки. Возможно этот трейт не нужен, ибо функцию можно поместить в нужный класс в качестве метода

require_once 'lib/autoload.php'; //функция автозагрузки классов

use core\Router; //пространство имен Роутера
session_start(); //так как используем сессии на сайте
(new Router())->run(); //запускаем главную функцию

//TODO везде есть сранный выбор по количеству параметров. Мне это не нравится, так как идет слишком сильная привязка к реализации. Придумать иное решение