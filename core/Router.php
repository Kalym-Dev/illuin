<?php

namespace core;
//Пространство имен нужно для того чтобы в автозагрузке классов в качестве имени передовался путь до файла с этим классом, можно заметить что имя пространства имен совпадает с именем директории, где лежит текуцщий класс
//Класс Router отвечает за формирование, обработку шаблонов и переадресацию согласно шаблонам

class Router
{

    private $routes; //массив, где хранятся наш массив из файла routes.php

    public function __construct(){
            $this->routes = require_once 'config/routes.php'; //здесь содержимое файла routes.php копируется в переменную routes класса Router
    }

    private function formating_patterns($pattern){ //фукнция, которая превращает ключи массива routes в регулярные выражения, то есть шаблоны
        $pattern = '#^' . $pattern . '$#';
        return $pattern;
    }

    private function match_single_pattern($pattern, $potential_route){ //фукнция осуществляющая проверку потенциаольного шаблона, шаблоном из массива routes
        return preg_match($this->formating_patterns($pattern), $potential_route);
    }

    private function get_request_path($url){ //получение URI, который потребовался для доступа к данной странице
        return (trim($url));
    }

    private function parseUrl($url){ //сравнение возможного шаблона со всеми возможными шшаблонами из массива routes
        $potential_route = $this->get_request_path($url); //получение URI
        $potential_route = substr(strpos($potential_route, '.')  ? strstr($potential_route, '.', true) : $potential_route, 1); //в переменной будет хранится либо пустота, либо название файла
        if(strpos($potential_route, '?')) $potential_route = substr($potential_route, 0, strpos($potential_route, '?')); //всегда убираем ? ибо он нам не нужен
        //TODO добавить в качестве сравнения регулярные выражения
        foreach($this->routes as $pattern => $contr_action){ //сравнение шаблонов
            if($this->match_single_pattern($pattern, $potential_route)){
                return $contr_action; //в случае успеха вернуть контроллер-метод этого шаблона
            }
        }
        return false; //иначе - false
    }
    private function parse_params($params = []){ //функция принимает в качестве аргумента GET-параметр, который превращается в число и возращает это число
        if(isset($params)) {
            $count_params = count($params);
            if($count_params === 1){ //если параметр один, то это id статьи, и нужно в $params засунуть id статьи
                if(isset($_REQUEST['id'])) {
                    $id = (int)$_REQUEST['id'];
                    $params = ['id' => $id];
                }
            }elseif($count_params === 2 and !empty($params['email']) and !empty($params['password'])){//если параметра два, то это логин и пароль, их нужно засунуть в $params
                    $params = ['email' => htmlspecialchars($_REQUEST['email']), 'password' => crypt(htmlspecialchars($_REQUEST['password']), htmlspecialchars($_REQUEST['email']))];
            }elseif($count_params === 2 and !empty($params['title']) and !empty($params['text'])){
              $params = ['title' => $_REQUEST['title'], 'text' => $_REQUEST['text']] ;
            } elseif($count_params === 3 and !empty($params['emai']) and !empty($params['author_name']) and !empty($params['password'])){//если параметра три, то это логин, пароль и имя автора (никнейм), их тоже нужно засунуть в $params
                $params = ['author_name' => htmlspecialchars($_REQUEST['author_name']), 'email' => htmlspecialchars($_REQUEST['email']), 'password' => crypt(htmlspecialchars($_REQUEST['password']), htmlspecialchars($_REQUEST['email']))];
            }
        }
//            $get_param = (int) $get_param;
        return $params;
    }
    private function createUrl($route){
        //TODO сделать функцию создания url исходя из маршутов
        //TODO найти в этой функции смысл
    }

    public function run(){ //запуск обработки
        $result = $this->parseUrl($_SERVER['REQUEST_URI']); //результат сравнения или преобразования url в шаблон
        $params = $this->parse_params($_REQUEST); //отправляем на обработку весь массив
//        @$params = (is_null ($this->parse_get_param($_GET['id']))) ? '' : ['id' => $this->parse_get_param($_GET['id'])]; //id-статьи. Идет проверка на нулевость парамтра, если он нулевой то в $params будет присвоена пустая строка, иначе будет присвоен id
        if(!empty($params['PHPSESSID'])) unset($params['PHPSESSID']);
        if($result){ //если вернуло контроллер-метод
            $contr_action = explode('/', $result); //делим строку контроллер/метод на массив в котором содержиться под индексом 0 - контроллер, под индексом 1 - метод
            //перменная $cont_action - это массив ['контроллер', ['действие_контроллера']
            $contr_action = array_combine(['controller', 'action'], $contr_action); // превращаем массив с числовыми индексами в массив с строковыми индексами (чтобыб понятнее было)
            $controller_name = 'controllers\\' .  ucfirst($contr_action['controller']) . 'Controller'; //Формируем имя контроллера с названия пространсва имен,  большой буквы и префиксом Controlller
            $path = $controller_name . '.php'; //формируем путь до файла с контроллером
            $path = str_replace('\\', '/', $path);
            if(file_exists($path)){ //проверяем, сущетсвует ли такой файл
                require_once $path; //если существет, подключаем его
                $controller = new $controller_name($contr_action); //создаем объект контроллера
                $method_of_controller = $contr_action['action'] . 'Action'; //формируем имя метода контроллера
                if(method_exists($controller, $method_of_controller)){ //проверям существет ли данный метод в контроллер
                    $controller->$method_of_controller($params); //если сущетсвет, то вызываем
                }else{
                    View::getError(404); // если нет, выводим на экран сообщение
                }
            }else{
               View::getError(404); //если не сущетсвет кнтроллер, то выводи сообщение
            }
        }else{
            View::getError(404); // если результат поиска - false, то возращаем ошибку
        }
    }
}

//TODO мне не нравится метод parse_params (очень сильная привзяка к реалихации)