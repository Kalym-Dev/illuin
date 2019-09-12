<?php

namespace error_classes; //пространство имен пользовательских классов ошибок

class FileOpenException extends \Exception //Этот класс ошибки наследуется от класса Exception
{
    use \Redirect_503; //используем трейт, для редиректа

    public function __construct($path, $code){ //в конструкторе принимаем путь, который НЕ ведет к файлу (то есть ошибочный путь) и пользовательский код ошибки
        parent::__construct($path, $code); //передаем эти параметры в конструктор базового класса
    }

    public function log_message(){ //метод отвечающий за формирование сообщения, которое запишется в лог-файл
        $message = date('d.m.y H:i:s') . ' #' . $this->code . ' Ошибка открытия файла ' . $this->message . ' Исключение выброшено в файле ' . $this->file . ' в строке ' . $this->line . PHP_EOL;
        error_log($message, 3, 'logs/log.txt'); //записываем сообщение в лог-файл
    }
}