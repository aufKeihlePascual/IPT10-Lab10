<?php

namespace App\Models;

class User extends BaseModel
{
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $password; #input only
    public $password_hash;

    public function register()
    {
        $this->password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->save('users'); 
    }

    public function login($username, $password)
    {
        $userData = $this->findByUsername($username); 
        
        if ($userData && password_verify($password, $userData['password_hash'])) {
            return $userData;
        }
        return false;
    }

    private function findByUsername($username)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(); 
    }

    public function getAllUsers()
    {
        global $conn;

        $stmt = $conn->query("SELECT id, first_name, last_name, email FROM users");
        return $stmt->fetchAll();
    }

    public function save($table)
    {
        $objectVars = get_object_vars($this);
        unset($objectVars['db']); 
        unset($objectVars['password']); 

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
