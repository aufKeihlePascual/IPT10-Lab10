<?php

namespace App\Models;

class User extends BaseModel
{
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $password; // This is for input only
    public $password_hash; // This is what will be saved in the database

    public function register()
    {
        // Hash the password and store it in the password_hash property
        $this->password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->save('users'); 
    }

    public function login($username, $password)
    {
        $userData = $this->findByUsername($username); 
        
        // Compare the entered password with the hashed password stored in the database
        if ($userData && password_verify($password, $userData['password_hash'])) {
            return true;
        }
        return false;
    }

    private function findByUsername($username)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(); // Return user data
    }

    public function save($table)
    {
        // Prepare an array of object variables to save, excluding the db property
        $objectVars = get_object_vars($this);
        unset($objectVars['db']); // Remove the db property from the array
        unset($objectVars['password']); // Exclude the plain password from the insert

        // Now, it only contains the fields to be inserted into the database
        $columns = implode(", ", array_keys($objectVars));
        $placeholders = implode(", ", array_fill(0, count($objectVars), '?'));
        $values = array_values($objectVars);

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt->execute($values)) {
            throw new \Exception('Failed to save data: ' . implode(", ", $stmt->errorInfo()));
        }
        return $this->db->lastInsertId();
    }
}
