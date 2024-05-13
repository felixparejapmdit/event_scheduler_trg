<?php

namespace API\Models;

use API\Database\DB;

class Event {

    protected $table = 'events';

    public static function getAll() {
        $db = DB::getInstance();
        $query = "SELECT * FROM events";
        $result = $db->query($query);
        return $result->fetchAll();
    }

    public static function find($id) {
        $db = DB::getInstance();
        $query = "SELECT * FROM events WHERE id = :id";
        $result = $db->query($query, [':id' => $id]);
        return $result->fetch();
    }

    public function save() {
        // Logic to save the event to the database
    }

    public function delete() {
        // Logic to delete the event from the database
    }
}
