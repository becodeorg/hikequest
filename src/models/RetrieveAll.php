<?php
declare(strict_types=1);

namespace Models;

use Controllers\MailController;
use PDO;

class retrieveAll extends Database
{
    public function findAll(): array
    {
        $sql = "SELECT 
        h.*,
        GROUP_CONCAT(t.Tags_Name) AS Tags,
        u.nickname 
    FROM Hikes h 
    INNER JOIN HikesTagsRelation htr ON htr.Htr_hikes_Id = h.Hikes_Id 
    INNER JOIN Tags t ON t.Tags_Id = htr.Htr_Tags_Id 
    RIGHT JOIN Users u ON u.User_id = h.H_User_Id 
    GROUP BY h.Hikes_Id, h.Hikes_Name, h.distance, h.duration, h.elevation_gain, h.description, u.nickname
    ";
        $stmt = $this->query($sql);
        $allDatas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $allDatas;
    }
    
    public function FindOneData(string $idHike): array|false
    {
        $stmt = $this->query(
            "SELECT h.*, GROUP_CONCAT(t.Tags_Name) AS Tags, u.nickname 
            FROM Hikes h 
            INNER JOIN HikesTagsRelation htr ON htr.Htr_hikes_Id = h.Hikes_Id 
            INNER JOIN Tags t ON t.Tags_Id = htr.Htr_Tags_Id 
            RIGHT JOIN Users u ON u.User_id = h.H_User_Id 
            WHERE h.Hikes_Id = ?
            GROUP BY h.Hikes_Id, h.Hikes_Name, h.distance, 
            h.duration, h.elevation_gain, h.description, u.nickname",
            [$idHike]
        );        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function findAllTags(): array
    {
        $sql = "SELECT * FROM Tags";
        $stmt = $this->query($sql);
        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $tags;
    }
    public function deleteHike(string $idHike)
    {
        $sqlDeleteRelations = "DELETE FROM `HikesTagsRelation` WHERE Htr_hikes_Id = ?";
        $sqlDeleteHike = "DELETE FROM `Hikes` WHERE Hikes_Id = ?";
        
        $this->query($sqlDeleteRelations, [$idHike]);
        $this->query($sqlDeleteHike, [$idHike]);
    }
}
    
