<?php

use App\Core\Router;

// define headers
Router::headers();
// Define routes and their associated controllers/methods
Router::http('get', '/api', ['ProductsController', 'getAll']);
Router::http('post', '/api', ['ProductsController', 'add']);
Router::http('delete', '/api/product', ['ProductsController', 'deleteById']);
Router::http('delete', '/api/products', ['ProductsController', 'deleteMultiple']);
Router::http('get', '/api/product', ['ProductsController', 'get']);
Router::http('patch', '/api/product', ['ProductsController', 'update']);

// Match and process the routes
Router::matchRoutes();
