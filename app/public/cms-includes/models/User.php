<?php

class User extends Database
{
    function __construct()
    {
        // call parent constructor
        parent::__construct();
    }

    // setup table user if not exists - make sure this code runs in signup.php ?
    public function setup()
    {
        $schema = "CREATE TABLE IF NOT EXISTS `cms-db`.`user` (
            `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `email` VARCHAR(100) NOT NULL,
            `password` VARCHAR(100) NOT NULL,
            `firstname` VARCHAR(45) NOT NULL,
            `lastname` VARCHAR(45) NOT NULL,
            `admin` TINYINT(1) NULL DEFAULT 0,
            PRIMARY KEY (`user_id`))
          ENGINE = InnoDB";
        $stmt = $this->db->prepare($schema);
        return $stmt->execute();
    }

    public function selectAll()
    {
        $sql = "SELECT * FROM user";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // add user to db
    public function insertOne($email, $hash_pass, $firstname, $lastname)
    {
        try {
            $sql = "INSERT INTO `user` (`user_id`, `email`, `password`, `firstname`, `lastname`, `position`) VALUES (NULL, '$email', '$hash_pass', '$firstname', '$lastname', '0')";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute();

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

    // update user
    public function updateOnePosition($id, $position)
    {
        $sql = "UPDATE user SET position = :position WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':position', $position, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function findOneEmail($email)
    {
        $sql = "SELECT * FROM user WHERE email='$email'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne($id)
    {
        $sql = "SELECT * FROM user WHERE user_id = '$id'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // create funtion that match password to found user[email]
    //  if (password_verify($password, $user['password'])){
    // public function matchPass($email, $password)
    // {
    //     // $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
       
    //     $sql = "SELECT * FROM user WHERE email='$email'";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute();



    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

}


?>