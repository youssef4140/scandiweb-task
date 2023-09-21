<?php 

namespace App\Controllers;

use App\Core\Controller;

use App\Models\Books;
use App\Models\Products;
use App\Models\Furniture;
use App\Models\Discs;


class ProductsController extends Controller
{

   public function add()
   {
    $request = $this->requestBody();
    $product = Products::add($request);
    $productType = $product->product_type;
    $productWithType = $product->$productType('add',$request);
    return $product;
   }

   public function get()
   {
    $id = $_GET['id'];
    $product = Products::find($id);
    $productType = $product->product_type;
    $productWithType = $product->$productType('find');
    return $productWithType;
   }
   
   public function getAllProducts()
   {
    $res = [];
    $products = Products::all();
    foreach($products as $product){
        $productType = $product['product_type'];
        $productInstance = Products::instance($product);
        $productWithType = $productInstance->$productType('find');
        $res[]= $productWithType;
    }
    return $res;
   }

   public function delete()
   {
    $request = $this->requestBody();
    $id = $_GET['id'];
    if($request){
        return $this->deleteMultiple($request);
    } else if (isset($_GET['id'])){
       return $this->deleteById($id);
    }
   }

   public function deleteMultiple($idArr)
   {
    $deleted = [];
    foreach($idArr as $id){
        $deleteState = $this->deleteById($id);
        $deleted[] = [$id=>$deleteState];
    }
    return $deleted;
   }

   public function deleteById($id)
   {
    $product = Products::find($id);
    return $product->delete();
   }



}