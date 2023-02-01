<?php
namespace src\app\Entity;

use src\Core\DB\Entity;
use src\Core\DB\DB;
use src\Core\Utils\Debug;

class Url extends Entity
{

    public static function findUrlsBy($id, $tableCategory)
    {
        $query = "SELECT url.id, url.name, url.url FROM url INNER JOIN url_skill_item as usi ON usi.url_id = url.id WHERE usi.{$tableCategory}_id = ? ORDER BY url.name;";
        $parameters = [$id];
        return DB::prepare($query, $parameters);
    }

    public static function search($keyword)
    {
        $pattern = '%' . $keyword . '%';
        $query = "SELECT * FROM url as u WHERE u.name LIKE :pattern OR u.url LIKE :pattern ORDER BY u.name;";
        $parameters = [':pattern' => $pattern];
        return DB::prepare($query, $parameters);
    }

    public static function addUrl($parameters)
    {
        $query = 'INSERT INTO `url`(`name`, `url`) VALUES (?,?)';
        $urlParameters = array($parameters['name'], $parameters['url']);
        DB::prepare($query, $urlParameters);

        $query = 'SELECT max(id) FROM url';
        $lastInsertId = DB::prepare($query);
        $skill_id = $parameters['skill_id'];
        $item_id = $parameters['item_id'];

        $urlSkillItemParameters = array($lastInsertId[0]["max(id)"], $skill_id, $item_id);
        $query = 'INSERT INTO `url_skill_item`(`url_id`, `skill_id`, `item_id`) VALUES (?,?,?)';
        return DB::prepare($query, $urlSkillItemParameters);
    }

    public static function deleteUrl($parameters)
    {
        $urlParameter = array($parameters['id']);
        $query = 'DELETE FROM `url_skill_item` WHERE url_id = ?;';
        DB::prepare($query, $urlParameter);
        $query = 'DELETE FROM `url` WHERE id = ?;';
        return DB::prepare($query, $urlParameter);
    }

}