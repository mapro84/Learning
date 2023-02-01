<?php
namespace src\app\Entity;

use src\Core\DB\Entity;
use src\Core\DB\DB;
use src\Core\Utils\Debug;

class Item extends Entity{

    public $name;
    public $urls;
    public $item_id;
    public $url;
    public $score;

    public static function search($parameters){
        $search = $parameters["search"] ?? null;
        if ($search === null)
            return [];
        $query = 'SELECT i.id, i.name, i.description, i.further,
         s.name as skillname, s.logo, s.id as skill_id
        FROM item as i 
        LEFT OUTER JOIN skill as s ON i.skill_id = s.id
        WHERE MATCH(i.name,i.description,i.further) against(?)
        OR MATCH(s.name) against(?) ORDER BY i.name;';
        $queryParams = [$search, $search];
        return DB::prepare($query, $queryParams);
    }

    public static function searchBySkillId($skill_id){
        $query = 'SELECT i.id, i.name, i.description, i.further, 
        s.name as skillname, s.logo, s.id as skill_id
        FROM item as i 
        LEFT OUTER JOIN skill as s ON i.skill_id = s.id
        WHERE i.skill_id = ?  ORDER BY i.name;';
        $queryParams = [$skill_id];
        return DB::prepare($query, $queryParams);
    }

    public static function deleteUrlSkillItem($item_id){
      $query = 'DELETE FROM `url_skill_item` WHERE skill_id is NOT NULL AND item_id = ?;';
      $queryParams = [];
      array_push($queryParams,$item_id);
      return DB::prepare($query, $queryParams);    
    }

    public static function deleteItemsBySkillId($skill_id){
        $query = 'DELETE FROM `item` WHERE skill_id = ?;';
        $queryParams = [];
        array_push($queryParams,$skill_id);
        return DB::prepare($query, $queryParams);    
      }

}