<?php

namespace App\Core;

use App\Core\Database;

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



    public static function find($id, $identifier = null)
    {
        try {

            if ($identifier === null)
                $identifier = 'id';
            $name = strtolower(self::name());
            $db = new Database;
            $sql = 'SELECT * FROM ' . $name . ' WHERE ' . $identifier . ' = :' . $identifier;
            $stmt = $db->pdo->prepare($sql);
            $stmt->execute([$identifier => $id]);
            $result = $stmt->fetch();
            if(!$result){
                http_response_code(404);
                die(json_encode([false, "No existing record with ".$identifier."=".$id]));
            };
            return self::instance($result);
        } catch (PDOException $e) {
            self::error($e);
        }

    }

    public static function add($product)
    {
        try {
            $db = new Database;
            $product = self::filter($product);
            [$placeHolders, $columns] = static::placeHolders($product);
            $table = strtolower(self::name());
            $sql = "INSERT INTO " . $table . " (" . $columns . ")
           VALUES (" . $placeHolders . ")";
            $stmt = $db->pdo->prepare($sql);
            $success = $stmt->execute($product);
            if ($success) {
                $id = $db->pdo->lastInsertId();
                $record = static::find($id);
                return $record;
            }
        } catch (PDOException $e) {
            self::error($e);
            // Re-throw the exception
        }
    }

    public static function all()
    {
        try {
            $db = new Database;
            $table = strtolower(self::name());
            $sql = "SELECT * FROM " . $table;
            $stmt = $db->pdo->prepare($sql);
            $success = $stmt->execute();
            if ($success) {
                $records = $stmt->fetchAll();
                return $records;
            }
        } catch (PDOException $e) {
            self::error($e);
        }
    }

    public function delete()
    {
        try {
            $db = new Database;
            $table = strtolower(self::name());
            $sql = 'DELETE FROM ' . $table . '
         WHERE id = :id';
            $stmt = $db->pdo->prepare($sql);
            return $stmt->execute(['id' => $this->id]);
        } catch (PDOException $e) {
            self::error($e);
        }

    }

    public function update($product, $id, $identifier = 'id')
    {
        try {
            $db = new Database;
            $table = strtolower(self::name());
            $product = self::filter($product);
            $placeHolder = $this->updatePlaceHolders($product, $identifier);
            $sql = 'UPDATE ' . $table . ' 
                    SET ' . $placeHolder . ' 
                    WHERE ' . $identifier . ' = :' . $identifier;
            $stmt = $db->pdo->prepare($sql);
            $success = $stmt->execute($product);
            if ($success) {
                $keys = static::keys();
                foreach ($keys as $key) {
                    $this->$key = $product[$key];
                }
                return $this;
            }
            ;
        } catch (PDOException $e) {
            self::error($e);
        }
    }

    public function updatePlaceHolders($products, $identifier)
    {
        unset($products[$identifier]);
        $keys = array_keys($products);
        $placeHolders = implode(
            ' , ',
            array_map(
                fn($key) =>
                $key . " = :" . $key,
                $keys
            )
        );
        return $placeHolders;

    }

    protected static function error(PDOException $e)
    {
        // die("Connection failed: " . $e->getMessage());        

        $sqlState = $e->getCode();
        http_response_code(500);
        switch ($sqlState) {
            case 23000:
                die(json_encode([false, "Duplicate SKU entry. Please enter a different SKU."]));
            case 'HY000':
                die(json_encode([false, $e->getMessage()]));
            default:
                die(json_encode([false, "An unknown error occurred. Please try again later."]));
        }
    }

}