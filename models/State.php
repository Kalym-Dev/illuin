<?php


namespace models;

use core\Model;

class State extends Model
{
    public function write_state($params){
        $result_of_write = $this->db->count_row('INSERT INTO posts (date, title, text, likes, views, author, author_id) VALUES (:date, :title, :text, :likes, :views, :author, :author_id)', $params);
        return $result_of_write;
    }

    public function edit_state($params){
        $result_of_update = $this->db->count_row('UPDATE posts SET title = :title, text = :text WHERE id = :id', $params);
        return $result_of_update;
    }

    public function delete_state($param){
        $result_of_delete = $this->db->count_row('DELETE FROM posts WHERE id = :id', $param);
        return $result_of_delete;
    }
    public function get_state_data($param){
        $result = $this->db->row('SELECT title, text, id FROM posts WHERE id = :id', $param);
        return $result;
    }
}

