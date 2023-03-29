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
           `user_id` int(10) UNSIGNED NOT NULL,
            `email` varchar(100) NOT NULL,
            `password` varchar(100) NOT NULL,
            `firstname` varchar(45) NOT NULL,
            `lastname` varchar(45) NOT NULL,
            `position` tinyint(1) DEFAULT 0,
            PRIMARY KEY (`user_id`))
            ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
            
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

    // delete user
    public function deleteOne($id)
    {
        $sql = "DELETE FROM user WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } 

    // update user
    public function updateOne($id, $email, $firstname, $lastname)
    {
        $sql = "UPDATE user SET email = :email, firstname = :firstname, lastname = :lastname WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // update users position
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
}

?>