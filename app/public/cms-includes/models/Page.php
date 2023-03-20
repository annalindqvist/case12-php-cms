<?php


class Page extends Database
{
    function __construct()
    {
        // call parent constructor
        parent::__construct();
    }

    public function setup()
    {
        $schema = "CREATE TABLE IF NOT EXISTS `mydb`.`page` (
            `page_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `page_name` VARCHAR(45) NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL,
            `published` TINYINT(1) NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `content` TEXT NULL,
            PRIMARY KEY (`page_id`),
            UNIQUE INDEX `text_UNIQUE` (`page_name` ASC) VISIBLE,
            INDEX `fk_page_user_idx` (`user_id` ASC) VISIBLE,
            CONSTRAINT `fk_page_user`
              FOREIGN KEY (`user_id`)
              REFERENCES `mydb`.`user` (`user_id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION)
          ENGINE = InnoDB";
        $stmt = $this->db->prepare($schema);
        return $stmt->execute();
    }

    // här följer olika exempel där placeholders används för att undvika SQL injections
    
    public function selectAll()
    {
        $sql = "SELECT * FROM page";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // public function selectAllOrderBy($column = 'information', $asc = true)
    // {

    //     // avoid sql injection building sql query
    //     switch ($column) {
    //         case 'position':
    //             $sql = "SELECT * FROM template ORDER BY position";
    //             break;
    //         case 'id':
    //             $sql = "SELECT * FROM template ORDER BY id";
    //             break;
    //         default:
    //             $sql = "SELECT * FROM template ORDER BY information";
    //             break;
    //     }

    //     $order = $asc === true ? 'ASC' : 'DESC';
    //     $sql = $sql . " $order"; 

    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute();

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // funktion för att lägga till data i tabellen
    public function insertOne($content, $user_id, $page_name)
    {

        try {
            //code...
            // PUBLISHED SHOULD COME FROM FORM
            $sql = "INSERT INTO page (content, user_id, page_name, published) VALUES (:content, :user_id, :page_name, 0)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':page_name', $page_name, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $this->db->lastInsertId();
    

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteOne($id)
    {
        $sql = "DELETE FROM page WHERE page_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } 

    // public function deleteMany($array)
    // {
    //     // ...
    // }

    // updatera
    public function updateOne($content, $page_name, $id)
    {
        $sql = "UPDATE template SET content = :content, page_name = :page_name WHERE page_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':page_name', $page_name, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function findOne($id)
    {
        $sql = "SELECT * FROM page WHERE page_id = '$id'";
        $stmt = $this->db->prepare($sql);
        //$stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


?>