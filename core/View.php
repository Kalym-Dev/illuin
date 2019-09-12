<?php


namespace core;

use error_classes;

class View
{
    private $route_of_controller; //маршут контроллера
    private $route_of_action; //маршут экшона

    public function __construct(array $route){ //в конструктор приходит массив вида ['контроллер' => 'имя_контроллера', 'экшон' => 'имя_экшона']
        $this->route_of_controller = $route['controller']; //сохранение имени контроллера в свойство класса
        $this->route_of_action = $route['action']; //сохранение имени экшона в свойство класса
    }

    public function render(array $data = []){ //фукнция подкючения файлов с разметкой и передачи им нужных данных. В качестве аргументов принимает данные о статье/стаьях (эти данные из БД)
        $path_to_view = 'views\\' . $this->route_of_controller . '\\' . $this->route_of_action . '.php'; //формирование путя до файла с разметкой
        $path_to_view = str_replace('\\', '/', $path_to_view);
        try {
            if (file_exists($path_to_view)) { //проверка на существование файл
                require_once $path_to_view; //если существует, то подключаем
                exit();
            }else{
                throw new error_classes\FileOpenException($path_to_view, 1);
            }
        }catch(error_classes\FileOpenException $exception){
            $exception->log_message();
            $exception->redirect();
        }
    }

    public function redirect($url){
        header('Location: ' . $url);
        exit();
    }

    public static function getError($error_code){ //статический метод для вывода ошибок (Он статический чисто для удобства) принимает на вход код ошибки
        $path_to_error = 'views\\errors\\' . $error_code . '.php'; //формирование путя до файла с разметкой ошибки
        $path_to_error = str_replace('\\', '/', $path_to_error);
        try {
            if (file_exists($path_to_error)) { //проверка на существование файла
                require_once $path_to_error; //если он сущетсвует, то подкючаем
                exit();
            }else{
                throw new error_classes\FileOpenException($path_to_error, 1);
            }
        }catch(error_classes\FileOpenException $exception){
            $exception->log_message();
            $exception->redirect();
        }
    }
}