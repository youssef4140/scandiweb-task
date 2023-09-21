<?php 

    namespace App\Services;

    class MySql
    {   
        public static function selectAll()
        {
            return 
            'SELECT p.*, 
            d.size, 
            b.weight, 
            f.dimension_l, f.dimension_h, f.dimension_w
            FROM Products p
            LEFT JOIN Discs d ON p.id = d.product_id
            LEFT JOIN Books b ON p.id = b.product_id
            LEFT JOIN Furniture f ON p.id = f.product_id
            ORDER BY p.id';
        }

        public static function delete()
        {
            return 
            "DELETE FROM products
             WHERE id = :id";
        }

        public static function update($table, $placeHolder, $identifier)
        {
            return 
            'UPDATE '.$table.'
            SET '.$placeHolder.'
            WHERE '.$identifier.' = :'.$identifier; 
        }

        public static function insert($table, $columns, $placeHolders)
        {
            return
            "INSERT INTO ".$table." (".$columns.")
            VALUES (".$placeHolders.")"; 
        }

        public static function selectById(){
            return 
            'SELECT p.*, 
            d.size, 
            b.weight, 
            f.dimension_l, f.dimension_h, f.dimension_w
            FROM Products p
            LEFT JOIN Discs d ON p.id = d.product_id
            LEFT JOIN Books b ON p.id = b.product_id
            LEFT JOIN Furniture f ON p.id = f.product_id 
            WHERE id = :id';
        }

        public static function findById($name)
        {
            return
            'SELECT * FROM {$name}
            LEFT JOIN discs
            ON product.id = discs.product_id';
        }

        public static function join($parentName,$childName,$id)
        {
            return
            'SELECT * FROM '.$parentName.'
            LEFT JOIN discs
            ON '.$parentName.'.id = '.$childName.'.'.$id.'';
        }
    }