<?php
//Функция автозагрузки классов
require_once 'error_classes/FileOpenException.php'; //подключаем производный класс от Exception. Класс, который мы подключили отвечает за обработку ошибок подключения файлов
require_once 'error_classes/DbOpenException.php';

spl_autoload_register(function ($class_name){
    $path = str_replace('\\', '/', $class_name . '.php'); //формирование путя до файла
    try { //блок TRY, здесь может быть ошибка
        if (file_exists($path)) { //проверка на существование
            require_once $path; //в случае существования, подключаем файл
        }else{ //если такого файла нет, то
            throw new error_classes\FileOpenException($path, 1); //выбрасываем исключение
        }
    }catch (error_classes\FileOpenException $exception){ //ловим это исключение
        $exception->log_message(); //метод записывающий сообщение в лог
        $exception->redirect(); //редирект на 503 страницу
    }
});