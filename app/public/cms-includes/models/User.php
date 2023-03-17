<?php

class User extends Database
{
    function __construct()
    {
        // call parent constructor
        parent::__construct();
    }

    // add user to db, only adds email and password to db, why not firstname and lastname??
    public function insertOne($email, $pass, $firstname, $lastname)
    {
        try {
            $sql = "INSERT INTO `user` (`user_id`, `email`, `password`, `firstname`, `lastname`, `admin`) VALUES (NULL, '$email', '$pass', '$firstname', '$lastname', '0')";
            $stmt = $this->db->prepare($sql);
            // $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            // $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
            // $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
            // $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteOne($id)
    {
        $sql = "DELETE FROM template WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } 

    // public function deleteMany($array)
    // {
    //     // ...
    // }

    // updatera
    public function updateOne($id, $information)
    {
        $sql = "UPDATE template SET information = :information WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':information', $information, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function findOneEmail($email)
    {
        $sql = "SELECT * FROM user WHERE email='$email'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne($email, $password)
    {
        $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


?>