<?php

namespace App\Models;

use App\Helpers\MySqlHelper;

use App\Core\Model;

use App\Core\Database;

use PDOException;

// use App\Models\Discs;

// use App\Models\Books;

// use App\Models\Furniture;


    class Products extends Model
    {
        public $id;
        public $sku;
        public $name;
        public $price;
        public $product_type;


        public function discs($method,$product = null)
        {
            return $this->type($method,$product);
        }
     
        public function books($method,$product)
        {
            return $this->type($method,$product);

        }

        public function furniture($method,$product)
        {
            return $this->type($method,$product);

        }


  
        private function type($method,$product = null)
        {
            $productTypeClass = 'App\\Models\\'.ucfirst($this->product_type);
            switch ($method) {
                case "add":
                    $product['product_id']=$this->id;
                    $this->product = $productTypeClass::add($product);
                    return $this->product;
                case "find":
                    $this->product = $productTypeClass::find($this->id,'product_id');
                    return $this->product;
                case "update":
                    $this->product = $productTypeClass::find($this->id,'product_id');
                    $this->product->update($product,$this->id,'product_id');
                    return $this->product;
                    break;
                default:
                    echo "Your favorite color is neither red, blue, nor green!";
                }
        }

    }

