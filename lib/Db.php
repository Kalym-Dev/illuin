<?php
namespace lib; //Задааем пространство имен

use PDO; //используем пространство имен PDO для того чтобы обратиться к классу PDO
use error_classes; //испльзуем пространство имен классов ошибок, чтобы обратиться к ним

class Db //задаем класс Db
{
    private $db; //объект подключения к базе данных

    public function __construct(){ //конструктор класса Db
        $config = require 'config/db_configs.php'; //получаем массиив с конфигурацией бд
        try { //начало возможных проблем (каламбур)
            $this->db = new PDO('mysql:host=' . $config['hosts'] . ';dbname=' . $config['db'] . ';charset=' . $config['charset'], $config['user'], $config['passw']); // подключаемся к нашей бд
        }catch (\PDOException $exception){ //ловим исключение, которое выбрасит конструктор класса PDO
            try{ //здесь же, в этом случае выбрасываем исключения пользовательского типа DbOpenException, с прописанным пространством имен
                throw new error_classes\DbOpenException($exception->getMessage(), 2); //передаем конструктору DbOpenException сообщение о ошибке класса PDOException и пользовательский код о ошибке
            }catch (error_classes\DbOpenException $exception){ //ловим исключение, выброшенное
                $exception->log_message(); //записываем в лог сообщение о ошибке
                $exception->redirect(); //редирект на страницу о ошибке
            }
        }
    }

    private function query($sql, $params = []){ //метод выполнения запроса
            $stmt = $this->db->prepare($sql); // подготовка SQL-запроса. Это сделано для того чтобы было удобнее задавать параметры через псевдопеременные типа :имя_переменной
            if(!empty($params)){ //если масив $params не пустой, то выполняем цикл foreach
            foreach ($params as $name_of_param => $val_of_param){ //в массиве $params находятся параметры типа 'имя_параметра' => 'значение_параметра'
                $stmt->bindValue(':' . $name_of_param, $val_of_param); //происходит проход по массиву $params. В $name_of_param находится 'имя_параметра', а в $val_of_param находится 'значение_параметра'
                //в методе bindValue происходит поиск по :имя_параметра, если находится совпадение, то кострукция :имя_параметра заменяется на значение_параметра
                //после выполнянеия метода получаем сформированный SQL-запрос
            }
        }
                $stmt->execute(); //выполняем SQL-запрос
                return $stmt; //возращаем, то что пришло из бд по нашему запросу. В переменной $stmt находится объект PDOStatement
    }

    public function row($sql, $params = []){ //метод, возращающая строки (ряды) из базы данных исходя из параметров в массиве $params
        $result = $this->query($sql, $params); //результат выполнения SQL-запроса. В $result хранится PODStatement
        return $result->fetchAll(PDO::FETCH_ASSOC); //для объекта $result выполняем метод fethAll с аргументом PDO::FETCH_ASSOC, это нужно для того чтобы возратился двумерный ассоциативный массив
        //двумерный ассоциативный массив вида [порядковый_номер_возращаемой_строки (ряда)] => ['id' = 'id_статьи', 'date' => 'дата_написания_статьи', 'title' => 'загаловок_статьи', 'text' => 'текст_статьи', 'likes' => 'количество_лайков', 'views' => 'количество_просмотров', 'author' => 'имя_автора']
    }

    public function count_row($sql, $params = []){ //функция подсчета количества возращенных строк из базы данных
        $result = $this->query($sql, $params); //выполняем SQL-команду
        return $result->rowCount(); //возращаем количество строк затронутых SQL-запросом
    }

}