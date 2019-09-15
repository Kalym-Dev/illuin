<?php


namespace controllers;

use core\Controller;

class StateController extends Controller
{
    public function writeAction($params)
    {
        if ($_SESSION['user']['user_data']['status'] == 1) { //проверка на то что потвержден ли аккаунт по почте
            if (!empty($params)) { //если да, то смотрим пусты ли параметры
                if (!empty($params['title'] and !empty($params['text']))) { //по отдельности проверяем
                    $title = $params['title'];
                    $text = $params['text'];

                    $text = $this->validatetext($text); //сомнительная функция, исправить

                    $title = htmlspecialchars($title);
                    $text = htmlspecialchars($text);

                    $params = ['date' => date('y-m-d H:i:s'), 'title' => $title, 'text' => $text, 'likes' => 0, 'views' => 0, 'author' => $_SESSION['user']['user_data']['author_name'], 'author_id' => $_SESSION['user']['user_data']['author_id']];
                    //приводим парамтры к приличному массиву
                    $result = $this->model->write_state($params); //результат письма
                    if (!$result) {
                        echo 'Ошибка';
                    } else {
                        $this->view->redirect('index.php');
                    }
                } else {
//                    echo 'Неполные данные!';
                }
            } else {
//                echo 'Неполные данные!';
            }
            $this->view->render();
        }else{
            $this->view->redirect('index.php');
        }
    }
    private function validatetext($text){
//        if(stristr($text, '<p>') and stristr($text, '</p>'))
            return $text;
    }

    public function editAction($params){
        if(array_key_exists('title', $params) and array_key_exists('text', $params) and array_key_exists('id', $params)){ //если передается id, title, text то значит что поступил запрос на изменение статьи
            $res = $this->model->edit_state($params);
            $this->view->redirect('index.php');
        }elseif(array_key_exists('id', $params)){ //если передается id, то
            $data_of_state = $this->model->get_state_data($params); //в массив $data_of_state передаем данные о статье
            foreach($data_of_state as $data_of_state){
                $data_of_state = $data_of_state;
                $this->view->render($data_of_state);
            }
        }
    }

    public function deleteAction($param){
        $result = $this->model->delete_state($param);
        if($result){
            $this->view->redirect('index.php');
        }else{
            echo 'Ошибка';
        }
    }
}

//TODO сделать что-нибудь с фукнцией validatetext. Изменить ее и улучшить, сделать ее более интелектуальной
//TODO убрать эти идиотские заглушки из else
//TODO повысить безопастность
//TODO повысить контроль качества входящих данных
//TODO во всех формах регистрации, ввода, входа выводится сообщение о неполноте/отсутвии данных - исправить

