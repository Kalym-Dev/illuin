<?php

namespace error_classes; //пространство имен пользовательских классов ошибок

class DbOpenException extends \PDOException //Класс ошибки соединения с бд. Наследуется от PDOException
{
    use \Redirect_503; //используем трейт, для метода редиректа

    public function __construct($message, $code){ //в конструктор на вход передается сообщение о ошибке и пользовательский код ошибки
        parent::__construct($message, $code); //эти же аргументы передаются конструктору-родителю и он же вызывается
    }

    public function log_message(){//метод отвечающий за формирование сообщения, которое запишется в лог-файл
        $message = date('d.m.y H:i:s') . ' #' . $this->code . ' Ошибка подключения к базе данных. ' . $this->message . ' Исключение выброшено в файле ' . $this->file . ' в строке ' . $this->line . PHP_EOL;
        error_log($message, 3,'logs/log.txt');
    }
}