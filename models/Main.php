<?php

namespace models;

use core\Model;

class Main extends Model
{
    public function get_posts($params = []){ //функция возрата статей
        if(!empty($params)){ //если в $params что-то есть (должно быть id статьи)
            $result = $this->db->row('SELECT * FROM posts WHERE id = :id', $params); //то выполняем запрос с параметрами
        }else{
            $result = $this->db->row('SELECT * FROM posts ORDER BY date DESC'); //иначе выполняем запрос без параметров
        }
        return $result; //возращаем результат запроса, в виде массива
    }


}