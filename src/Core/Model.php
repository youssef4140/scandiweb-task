<?php

namespace App\Core;

use PDOException;

/**
 * Base class for database models.
 */
class Model
{
    /**
     * Get the name of the model class.
     *
     * @return string The name of the model class.
     */
    private static function name(): string
    {
        return explode('\\', static::class)[2];
    }

    /**
     * Get an array of property keys for the model class.
     *
     * @return array An array of property keys.
     */
    private static function keys(): array
    {
        return array_keys(get_class_vars(static::class));
    }

    /**
     * Filter an array of products based on allowed keys.
     *
     * @param array $products The array of products to filter.
     * @return array The filtered array of products.
     */
    private static function filter(array $products) :array
    {
        $allowedKeys = static::keys();

        foreach ($products as $key => $value) {
            if (!in_array($key, $allowedKeys)) {
                unset($products[$key]);
            }
        }

        return $products;
    }

    /**
     * Generate placeholders and column names for SQL queries.
     *
     * @param array|null $keys An optional array of keys to generate placeholders for.
     * @return array An array containing placeholders and column names.
     */
    private static function placeHolders($keys = null) :array
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

    /**
     * Create an instance of the model with given properties.
     *
     * @param array $properties An array of properties to set.
     * @return object An instance of the model.
     */
    public static function instance(array $properties) :object
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

    /**
     * Find a record by ID and optional identifier.
     *
     * @param int $id The ID to search for.
     * @param string|null $identifier An optional identifier column.
     * @return object An instance of the model.
     * @throws \Exception If the record is not found, throws a 404 exception.
     * @throws PDOException If a database error occurs.
     */
    public static function find(int $id, $identifier = null) :object
    {
        try {
            if ($identifier === null) {
                $identifier = 'id';
            }
            $name = strtolower(self::name());
            $db = new Database;
            $sql = 'SELECT * FROM ' . $name . ' WHERE ' . $identifier . ' = :' . $identifier;
            $stmt = $db->pdo->prepare($sql);
            $stmt->execute([$identifier => $id]);
            $result = $stmt->fetch();
            if (!$result) {
                throw new \Exception(json_encode([false, "No existing record with " . $identifier . "=" . $id]), 404);
            }
            return self::instance($result);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Add a new record to the database.
     *
     * @param array $product An array of product data to add.
     * @return object|null An instance of the model representing the added record.
     * @throws PDOException If a database error occurs.
     */
    public static function add(array $product) 
    {
        try {
            $db = new Database;
            $product = self::filter($product);
            [$placeHolders, $columns] = static::placeHolders($product);
            $table = strtolower(self::name());
            $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $placeHolders . ")";
            $stmt = $db->pdo->prepare($sql);
            $success = $stmt->execute($product);
            if ($success) {
                $id = $db->pdo->lastInsertId();
                $record = static::find($id);
                return $record;
            } 
            return null;
    
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Retrieve all records from the database.
     *
     * @return array An array of all records.
     * @throws PDOException If a database error occurs.
     */
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
            throw $e;
        }
    }

    /**
     * Delete the current record from the database.
     *
     * @return bool True if the record was deleted, false otherwise.
     * @throws PDOException If a database error occurs.
     */
    public function delete() :bool
    {
        try {
            $db = new Database;
            $table = strtolower(self::name());
            $sql = 'DELETE FROM ' . $table . ' WHERE id = :id';
            $stmt = $db->pdo->prepare($sql);
            return $stmt->execute(['id' => $this->id]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Update the current record with the provided data.
     *
     * @param array $product An array of product data to update.
     * @param string $id The ID of the record to update.
     * @param string $identifier An optional identifier column.
     * @return mixed An instance of the model representing the updated record.
     * @throws PDOException If a database error occurs.
     */
    public function update(array $product,string $identifier = 'id')
    {
        try {
            $db = new Database;
            $table = strtolower(self::name());
            $product = self::filter($product);
            $placeHolder = $this->updatePlaceHolders($product, $identifier);
            if(!$placeHolder) return;
            $sql = 'UPDATE ' . $table . ' SET ' . $placeHolder . ' WHERE ' . $identifier . ' = :' . $identifier;
            $stmt = $db->pdo->prepare($sql);
            $success = $stmt->execute($product);
            // if ($success) {
            //     return self::find($product['id'],$identifier);
            // }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Generate update placeholders for SQL query.
     *
     * @param array $products An array of product data.
     * @param string $identifier The identifier column name.
     * @return string A string containing update placeholders.
     */
    public function updatePlaceHolders(array $products, string $identifier): string
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
}
