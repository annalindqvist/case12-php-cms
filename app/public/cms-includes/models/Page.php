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
            `page_id` int(10) UNSIGNED NOT NULL,
            `page_name` varchar(45) NOT NULL,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT NULL,
            `published` tinyint(1) NOT NULL,
            `content` text NOT NULL,
            `user_id` int(10) UNSIGNED NOT NULL,
            `menu_priority` int(11) DEFAULT NULL, 
           
            PRIMARY KEY (`page_id`),
            UNIQUE INDEX `text_UNIQUE` (`page_name` ASC) VISIBLE,
            INDEX `fk_page_user_idx` (`user_id` ASC) VISIBLE,
            CONSTRAINT `fk_page_user`
            FOREIGN KEY (`user_id`)
            REFERENCES `cms-db`.`user` (`user_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
            ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

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

    public function selectAllMenuPriority()
    {
        $sql = "SELECT * FROM page WHERE published = 1 ORDER BY menu_priority ASC, page_name ASC";
        $sql = $sql; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertOne($content, $user_id, $page_name, $visibility, $created_with)
    {
        try {
            $sql = "INSERT INTO page (content, user_id, page_name, published, tiny_mce) VALUES (:content, :user_id, :page_name, :visibility, :created_with)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':page_name', $page_name, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':visibility', $visibility, PDO::PARAM_INT);
            $stmt->bindValue(':created_with', $created_with, PDO::PARAM_INT);

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

    public function updateOne($page_content, $page_name, $page_id, $visibility, $created_with)
    {
        $timestamp = date('Y-m-d H:i:s');
        $sql = "UPDATE page SET content = :page_content, page_name = :page_name, updated_at = :timestamp, published = :visibility, tiny_mce = :created_with WHERE page_id = :page_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':page_name', $page_name, PDO::PARAM_STR);
        $stmt->bindValue(':page_content', $page_content, PDO::PARAM_STR);
        $stmt->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->bindValue(':page_id', $page_id, PDO::PARAM_INT);
        $stmt->bindValue(':visibility', $visibility, PDO::PARAM_INT);
        $stmt->bindValue(':created_with', $created_with, PDO::PARAM_INT);


        return $stmt->execute();
    }

    public function menuPriority($data)
    {
        foreach ($data as $page_name => $menu_priority) {
            $sql = "UPDATE page SET menu_priority='$menu_priority' WHERE page_name='$page_name'";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute();
            if ($result === FALSE) {
                return($result);
            }
        }
        return $result;
    }

    public function findOne($page_name)
    {
        $sql = "SELECT * FROM page WHERE page_name = '$page_name'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOnePublished($page_name)
    {
        $sql = "SELECT * FROM page WHERE page_name = '$page_name' AND published = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>