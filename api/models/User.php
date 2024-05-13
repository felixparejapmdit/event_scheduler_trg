<?php

namespace API\Models;

use API\Database\DB;

class User {

    protected $table = 'users';

    public static function getAll() {
        $db = DB::getInstance();
        $query = "SELECT * FROM users";
        $result = $db->query($query);
        return $result->fetchAll();
    }

    public static function find($id) {
        $db = DB::getInstance();
        $query = "SELECT * FROM users WHERE id = :id";
        $result = $db->query($query, [':id' => $id]);
        return $result->fetch();
    }

    public function save() {
        // Logic to save the user to the database
    }

    public function delete() {
        // Logic to delete the user from the database
    }
}
