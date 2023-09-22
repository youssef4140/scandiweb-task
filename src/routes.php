<?php



use App\Core\Router;
// use App\Core\Database;
// use App\Models\Books;
// use App\Models\Products;
// use App\Models\Furniture;
// use App\Models\Discs;
// use App\Controllers\ProductsController;



/** 
 * This code implements a basic routing functionality for handling HTTP requests using the Router class.
 * It uses explicit HTTP methods (post, get, patch, delete) for different actions.
 * The code creates an instance of the ProductsController class and assigns it to the $productsController variable.
 * It then defines four routes using the Router::http() method, specifying the HTTP method, URI, and callback function for each route.
 * The callback functions call methods on the $productsController object to handle the corresponding actions.
 * Finally, the Router::matchRoutes() method is called to check if any routes were matched. If not, a 404 Not Found response is sent.
 */



Router::http('post','/api',['ProductsController','add']);

Router::http('get','/api/products',['ProductsController','getAll']);

Router::http('get','/api/product',['ProductsController','get']);

Router::http('delete','/api/product',['ProductsController','delete']);

Router::http('patch','/api/product',['ProductsController','update']);







Router::matchRoutes();