<?php


class Template extends Database
{
    function __construct()
    {
        // call parent constructor
        parent::__construct();
    }

    public function setup()
    {
        $schema = "CREATE TABLE IF NOT EXISTS template (id INT NOT NULL AUTO_INCREMENT, information VARCHAR(50), position INT, PRIMARY KEY (id))";
        $stmt = $this->db->prepare($schema);
        return $stmt->execute();
    }

    // här följer olika exempel där placeholders används för att undvika SQL injections
    
    public function selectAll()
    {
        $sql = "SELECT * FROM user";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function selectAllOrderBy($column = 'information', $asc = true)
    {

        // avoid sql injection building sql query
        switch ($column) {
            case 'position':
                $sql = "SELECT * FROM template ORDER BY position";
                break;
            case 'id':
                $sql = "SELECT * FROM template ORDER BY id";
                break;
            default:
                $sql = "SELECT * FROM template ORDER BY information";
                break;
        }

        $order = $asc === true ? 'ASC' : 'DESC';
        $sql = $sql . " $order"; 

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    // funktion för att lägga till data i tabellen
    public function insertOne($information, $position)
    {

        try {
            //code...
            $sql = "INSERT INTO template (information, position) VALUES (:information, :position)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':information', $information, PDO::PARAM_STR);
            $stmt->bindValue(':position', $position, PDO::PARAM_INT);
            $stmt->execute();
    
            return $this->db->lastInsertId();
    

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

    public function findOne($email, $password)
    {
        $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


?>