<?php

namespace controllers; //пространство имен совпадает с дерикторией, где лежит данный класс. Это сделано для фукнции автозагрузки классов. Она воспринимает пространство имен как директорию, где лежит файл

use core\Controller; //чтобы наследовать от класса Controller
use core\View; //чтобы использовать методы класса View

class MainController extends Controller //задаем класс который наследуюет от класса Controller
{
    public function indexAction($params = []){ //функция показа всех новостей/статей


        if(empty($this->model->get_posts($params))){ //если исходя из параметров возращается пустой массив то
            View::getError(404); //выводим ошибку 404
        }
        $data = $this->model->get_posts($params); //массив, содержащий массивы с данными о статьиях
        foreach($data as $data) { //это чтоб добавить новый элемент в конец всех массивов
            $data['hesh'] = crypt($data['author_id'], $data['author']); //хеш id автора
            $final_data[] = $data; //вставка хеша в конец каждого массива
        }
        $this->view->render($final_data); //иначе рендерим страницу исходя из данных которые пришли нам из модели
   }
}