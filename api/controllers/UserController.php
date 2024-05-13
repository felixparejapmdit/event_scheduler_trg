<?php

namespace API\Controllers;

use API\Models\User;

class UserController {

    public function index() {
        // Logic to fetch all users
        // Example:
        $users = User::getAll();
        return json_encode($users);
    }

    public function show($id) {
        // Logic to fetch user by ID
        // Example:
        $user = User::find($id);
        if ($user) {
            return json_encode($user);
        } else {
            return json_encode(['error' => 'User not found'], 404);
        }
    }

    public function store() {
        // Logic to store a new user
        // Example:
        // Assuming data is sent via POST request
        $data = $_POST;
        $user = new User();
        $user->fill($data); // Assuming there's a fill method to fill model properties
        $user->save();
        return json_encode(['message' => 'User created successfully']);
    }

    public function update($id) {
        // Logic to update an existing user
        // Example:
        // Assuming data is sent via PUT or PATCH request
        $data = $_POST;
        $user = User::find($id);
        if ($user) {
            $user->fill($data); // Assuming there's a fill method to fill model properties
            $user->save();
            return json_encode(['message' => 'User updated successfully']);
        } else {
            return json_encode(['error' => 'User not found'], 404);
        }
    }

    public function destroy($id) {
        // Logic to delete a user
        // Example:
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return json_encode(['message' => 'User deleted successfully']);
        } else {
            return json_encode(['error' => 'User not found'], 404);
        }
    }

}
