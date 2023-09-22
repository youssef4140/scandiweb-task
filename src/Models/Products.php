<?php

namespace App\Models;

use App\Core\Model;

/**
 * Products class for managing various product types.
 */
class Products extends Model
{
    /**
     * @var int The ID of the product.
     */
    public $id;

    /**
     * @var string 
     */
    public $sku;

    /**
     * @var string 
     */
    public $name;

    /**
     * @var float 
     */
    public $price;

    /**
     * @var int
     */
    public $product_type;

    /**
     * Handle specific product type operations.
     *
     * @param string $method The operation method ("add", "find", "update").
     * @param mixed  $product The product data for the operation.
     * @return mixed The result of the operation.
     * @throws \Exception If an error occurs during the operation.
     */
    public function discs($method, $product = null)
    {
        return $this->type($method, $product);
    }

    /**
     * Handle specific product type operations.
     *
     * @param string $method The operation method ("add", "find", "update").
     * @param mixed  $product The product data for the operation.
     * @return mixed The result of the operation.
     * @throws \Exception If an error occurs during the operation.
     */
    public function books($method, $product = null)
    {
        return $this->type($method, $product);
    }

    /**
     * Handle specific product type operations.
     *
     * @param string $method The operation method ("add", "find", "update").
     * @param mixed  $product The product data for the operation.
     * @return mixed The result of the operation.
     * @throws \Exception If an error occurs during the operation.
     */
    public function furniture($method, $product = null)
    {
        return $this->type($method, $product);
    }

    /**
     * Perform product type-specific operations.
     *
     * @param string $method The operation method ("add", "find", "update").
     * @param mixed  $product The product data for the operation.
     * @return mixed The result of the operation.
     * @throws \Exception If an error occurs during the operation.
     */
    private function type($method, $product = null)
    {
        $productTypeClass = 'App\\Models\\' . ucfirst($this->product_type);
        try {
            switch ($method) {
                case "add":
                    $product['product_id'] = $this->id;
                    $this->product = $productTypeClass::add($product);
                    return $this->product;
                case "find":
                    $this->product = $productTypeClass::find($this->id, 'product_id');
                    return $this->product;
                case "update":
                    $this->product = $productTypeClass::find($this->id, 'product_id');
                    $this->product->update($product, 'product_id');
                    return $this->product;
                    break;
                default:
                    throw new \Exception("First parameter must be add,find or update");
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
