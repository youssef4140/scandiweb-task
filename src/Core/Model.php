<?php

namespace App\Core;

use App\Services\MySql;
use App\Core\Database;

use PDO;

use PDOException;

class Model
{
    private static function name(): string
    {
        return explode('\\', static::class)[2];
    }

    private static function keys(): array
    {
        return array_keys(get_class_vars(static::class));
    }

    private static function filter($products)
    {
        $allowedKeys = static::keys();

        foreach ($products as $key => $value) {
            if (!in_array($key, $allowedKeys)) {
                unset($products[$key]);
            }
        }

        return $products;
    }

    private static function placeHolders($keys = null)
    {
        if ($keys === null) {
            $keys = self::keys();
        }
        $keys = array_keys($keys);

        $columns = implode(', ', $keys);

        $placeHolders = implode(
            ', ',
            array_map(
                fn($key) =>
                ":" . $key,
                $keys
            )
        );
        return [$placeHolders, $columns];
    }

    public static function instance($properties)
    {
        $name = static::class;
        $instance = new $name();
        foreach ($properties as $propertyName => $propertyValue) {
            if (property_exists($name, $propertyName)) {
                $instance->{$propertyName} = $propertyValue;
            }
        }
    
        return $instance;

    }

   

    public static function find($id,$identifier = null)
    {
        if($identifier === null) $identifier = 'id';
        $name = strtolower(self::name());
        $db = new Database;
        $sql = 'SELECT * FROM '.$name.' WHERE '.$identifier.' = :'.$identifier;
        $statement = $db->pdo->prepare($sql);
        $statement->execute([$identifier=>$id]);
        return self::instance($statement->fetch());
    }

    public static function add($product) 
    {
        try{
            $db = new Database;
            $product = self::filter($product);
            [$placeHolders, $columns] = static::placeHolders($product);
            $table = strtolower(self::name());
           $sql =  "INSERT INTO ".$table." (".$columns.")
           VALUES (".$placeHolders.")";
           $statement = $db->pdo->prepare($sql);
           $success = $statement->execute($product);
           if ($success) {
               $id = $db->pdo->lastInsertId();
                $record = static::find($id);
                return $record;
           };
        } catch (PDOException $e){
            return $e->getMessage();
        }
    }

    public static function all()
    {
        try{
            $db = new Database;
            $table = strtolower(self::name());
           $sql =  "SELECT * FROM ".$table;
           $statement = $db->pdo->prepare($sql);
           $success = $statement->execute();
           if ($success) {
            $records = $statement->fetchAll();
            return $records;
           }
        } catch (PDOException $e){
            return $e->getMessage();
        }
    }
    
    public function delete()
    {
        try{
        $db = new Database;
        $table = strtolower(self::name());
        $sql = 'DELETE FROM '.$table.'
         WHERE id = :id';
        $statement = $db->pdo->prepare($sql);
        return $statement->execute(['id'=>$this->id]);
        } catch (PDOException $e){
            return $e->getMessage();
        }

    }

}
//     // echo self::class



//     // instance 
//     // delete()
//     // update(arr)


// }