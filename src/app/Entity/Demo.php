<?php
namespace src\app\Entity;

use src\Core\DB\DB;
use src\Core\DB\Entity;

class Demo extends Entity{

	public $item_id;
	
    public static function getDemosBySkillId(int $skill_id): array
    {
        $query = 'SELECT d.id, d.name, d.description FROM demo as d inner join item as i ON d.item_id = i.id'.
            ' INNER join skill as s ON s.id = i.skill_id where s.id = ? ORDER BY d.name;';
        $parameters = [$skill_id];
        return DB::prepare($query, $parameters);
    }

    public static function search($keyword)
    {
        $pattern = '%' . $keyword . '%';
        $query = "SELECT * FROM demo as d WHERE d.name LIKE :pattern OR d.description LIKE :pattern ORDER BY d.name;";
        $parameters = [':pattern' => $pattern];
        return DB::prepare($query, $parameters);
    }
	
}