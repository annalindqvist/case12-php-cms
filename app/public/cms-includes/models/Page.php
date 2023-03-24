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
        $schema = "CREATE TABLE IF NOT EXISTS `cms-db`.`page` (
            `page_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `page_name` VARCHAR(45) NOT NULL UNIQUE,
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
              REFERENCES `cms-db`.`user` (`user_id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION)
          ENGINE = InnoDB";
        $stmt = $this->db->prepare($schema);
        return $stmt->execute();
    }

    public function selectAll()
    {
        $sql = "SELECT * FROM page";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   

    public function selectAllPublished()
    {
        $sql = "SELECT * FROM page WHERE published = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllDrafts()
    {
        $sql = "SELECT * FROM page WHERE published = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // funktion för att lägga till data i tabellen
    public function insertOne($content, $user_id, $page_name, $visibility)
    {

        try {
            // PUBLISHED SHOULD COME FROM FORM
            $sql = "INSERT INTO page (content, user_id, page_name, published) VALUES (:content, :user_id, :page_name, :visibility)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':page_name', $page_name, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':visibility', $visibility, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteOne($page_name)
    {
        $sql = "DELETE FROM page WHERE page_name = :page_name";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':page_name', $page_name, PDO::PARAM_STR);
        return $stmt->execute();
    } 

    // updatera
    public function updateOne($page_content, $page_name, $page_id, $visibility)
    {
        $timestamp = date('Y-m-d H:i:s');
        $sql = "UPDATE page SET content = :page_content, page_name = :page_name, updated_at = :timestamp, published = :visibility WHERE page_id = :page_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':page_name', $page_name, PDO::PARAM_STR);
        $stmt->bindValue(':page_content', $page_content, PDO::PARAM_STR);
        $stmt->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->bindValue(':page_id', $page_id, PDO::PARAM_INT);
        $stmt->bindValue(':visibility', $visibility, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function findOne($page_name)
    {
        $sql = "SELECT * FROM page WHERE page_name = '$page_name'";
        $stmt = $this->db->prepare($sql);
        //$stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOnePublished($page_name)
    {
        $sql = "SELECT * FROM page WHERE page_name = '$page_name' AND published = 1";
        $stmt = $this->db->prepare($sql);
        //$stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


?>