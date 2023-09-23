<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Products;
use Exception;

/**
 * Controller class for managing products.
 */
class ProductsController extends Controller
{
    /**
     * Add a new product.
     *
     * @return object An instance of the added product.
     * @throws Exception If an error occurs while adding the product.
     */
    public function add(): object
    {
        try {
            $request = $this->requestBody();
            $product = Products::add($request);
            $productType = $product->product_type;
            $product->$productType('add', $request);
            return $product;
        } catch (Exception $e) {
            if($product) $product->delete();
            throw $e;
        }
    }

    /**
     * Get a product by ID.
     *
     * @return object An instance of the retrieved product.
     * @throws Exception If the product is not found or an error occurs.
     */
    public function get(): object
    {
        try {
            $id = $_GET['id'];
            if(!isset($id)) {
            throw new Exception(json_encode([false,"Need to add a query id"]),0);
            }
            $product = Products::find($id);
            $productType = $product->product_type;
            $product->$productType('find');
            return $product;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all products.
     *
     * @return array An array of product instances.
     * @throws Exception If an error occurs while retrieving products.
     */
    public function getAll(): array
    {
        try {
            $res = [];
            $products = Products::all();
            foreach ($products as $product) {
                $productType = $product['product_type'];
                $productInstance = Products::instance($product);
                $productInstance->$productType('find');
                $res[] = $productInstance;
            }
            return $res;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update a product.
     *
     * @return object An instance of the updated product.
     * @throws Exception If an error occurs while updating the product.
     */
    public function update()
    {
        try {
            $request = $this->requestBody();
            $id = $_GET['id'];
            $product = Products::find($id);
            // if($product->product_type !== $request['product_type']){
            //     throw new Exception(json_encode([false,'product_type cannot be changed'],0));
            // }
            $productType = $product->product_type;
            unset($request['product_type']);
            $product->update($request);
            $product->$productType('update', $request);

            // $product = Products::find($id);
            // $product->$productType('find');
            return $product;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete multiple products by their IDs.
     *
     * @return array An array indicating which products were deleted.
     */
    public function deleteMultiple(): array
    {
        $request = $this->requestBody();
        $deleted = [];
        foreach ($request as $id) {
            $deleteState = $this->deleteById($id);
            $deleted[] = [$id => $deleteState];
        }
        return $deleted;
    }

    /**
     * Delete a product by its ID.
     *
     * @param int|null $id The ID of the product to delete.
     * @return bool True if the product was deleted, false otherwise.
     * @throws Exception If an error occurs while deleting the product.
     */
    public function deleteById(int $id = null): bool
    {
        if ($id === null) {
            $id = $_GET['id'];
        }    
        if(!isset($id)){
            throw new Exception(json_encode([false,"Need to add a query id"]),0);
        }
        try {

            $product = Products::find($id);
            return $product->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
