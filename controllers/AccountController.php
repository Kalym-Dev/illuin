<?php


namespace controllers; //пространство имен контроллеров


use core\Controller; //используем пространство имен чтобы наследоваться от базового абстрактного класса

class AccountController extends Controller //собственно наследуемся
{
    public function loginAction($params){ //метод авторизации, который на вход принимает массив с паратремами email и пароль
        if(!@$_SESSION['user']['authorization']) { //если авторизации нет, то
            if (!empty($params)) { //проверяем на пустоту аргументы
                if (!empty($params['email'])) { //проверяем на пустоту поле email
                    $result = $this->model->validate_params($params); //если все ок, то передаем методу модели эти параметры
                    if ($result) { //если есть соответсвия
                        $_SESSION['user']['authorization'] = true; //создается сессия авторизации
                        $_SESSION['user']['user_data'] = $result;
                        $this->view->redirect('index.php'); //и редирект на главную страницу
                    }else{
//                       echo 'Неправильный логин или пароль!'; //если что-то пошло не так, выводится сообщение
                    }
                } else {
//                    echo 'Введите e-mail'; //если не заполнен email
                }
            }
        }else{
            $this->view->redirect('index.php'); //если существует сессия авторизации, то редирект на главную страницу
        }
        $this->view->render(); //рендерим нужное
    }

    public function registerAction($params){ //метод регистрации пользователя
        $user_data = $this->model->insert_params($params); //метод вставки данных пользователя в базу данны
        if(!@$_SESSION['user']['authorization']) {
                if (!empty($user_data)) { //если нас постиг успех, то
                    $_SESSION['user']['authorization'] = true; //создаем сессию авторизации
                    $_SESSION['user']['user_data'] = $user_data;
                    $this->view->redirect('index.php'); //перенаправляем на главную страницу
                } else {
//            echo 'Ошибка. Данные уже существуют!'; //иначе выводим сообщение о ошике
                }
        }else{
            $this->view->redirect('index.php');
        }
        $this->view->render(); //рендерим нужное
    }

    public function logoutAction(){ //выход из сессии авторизации
        unset($_SESSION['user']); //уничтожаем сессию пользователя
        $this->view->redirect('index.php'); //редиректим
    }
}

// TODO реализовать подтвержение по электронной почте