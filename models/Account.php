<?php


namespace models; //пространство имен моделей


use core\Model; //чтобы наследоваться от базового класса

class Account extends Model //наследуемся
{
    public function validate_params($params = []){ //функция на проверку соответсвия введенных данных данным из бд
        $result_of_matching = $this->db->count_row('SELECT * FROM authors WHERE email=:email AND password=:password', $params); //если есть соответсвие, то возращается количество соответсвий (то есть 1, потому что все пользователи уникальны), если ничего не найдено, то 0
        if($result_of_matching){ //если есть соответсвия
           $user_data = $this->get_users_data($params); //сохраняем данные пользователя
        }
        return @$user_data; //возращаем данные пользователя
    }
    public function insert_params($params){ //метод вставки данных пользователя в базу данных
        if (!empty($params)) { //если аргуент не пустой
            $validate_params = ['email' => $params['email'], 'author_name' => $params['author_name']]; //формируем массив с параметрами
            $result_of_matching = $this->db->count_row('SELECT * FROM authors WHERE email=:email OR author_name=:author_name', $validate_params); //возращаем количество совпадений
            $result_of_matching = (bool) $result_of_matching; //количество совпадений переводим в булево значение
            if (!$result_of_matching) { //если совпадений нет, то
                $result_of_insert = $this->db->count_row('INSERT INTO authors (author_name, email, password) VALUES (:author_name, :email, :password)', $params); //здесь выполняется вставка значений, чтобы добиться отображения результате о провале или успехе приходится подсчитывать количество затроннутых строк
                if($result_of_insert) {//если вставка прошла успешшно, то
                    $user_data = $this->get_users_data($params); //сохраняем данные пользователя
                    return $user_data;//возращается данные пользователя
                }
            }else { //иначе
                return [];
            }
        }else{
            return [];
        }
    }

    private function get_users_data($params){ //функция возрата данных пользователя

           if(count($params) == 3){
               $user_data = $this->db->row('SELECT author_id, author_name, email, status FROM authors WHERE email=:email AND password=:password AND author_name=:author_name', $params); //возращаем id автора, его имя и почту из бд
           }elseif(count($params) == 2){
               $user_data = $this->db->row('SELECT author_id, author_name, email, status FROM authors WHERE email=:email AND password=:password', $params); //возращаем id автора, его имя и почту из бд
           }
            foreach($user_data as $value) { //эта конструкция нужна чтобы миновать лишний уровень массива
                $user_data = $value;
            }
            return $user_data;
    }
}

//TODO метод get_users_data - слишком сильная привязка к реализации