<?php

namespace App\Core;

header('Content-Type: application/json');

class Router
{
    /** matched is used as a flag to check if the http request matches any of the routes */
    private static $matched = false; 

    /** 
     * http function checks if the uri is a substring of the $_SERVER['REQUEST_URI'],
     * and if the $_SERVER['REQUEST_METHOD'] matches the parameter method,
     * if they match the matched flag is set to true and the callback is called
     * 
     * @param string $method 
     * @param string $uri
     * @param array $callback
     */


    public static function http($httpMethod,$httpUri,$callback )
    {
         $requestUri = $_SERVER['REQUEST_URI'];
         $requestUri = explode('?', $requestUri)[0];
         if($requestUri === $httpUri
         && $_SERVER['REQUEST_METHOD'] == strtoupper($httpMethod)) {
            $fileName = $callback[0];
            $fileDirectory = dirname(__DIR__).DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.$fileName.'.php';
            if(!file_exists($fileDirectory))echo "File doesn't exist";
            $className = 'App\\Controllers\\'.$callback[0];
            if(!class_exists($className))echo "Class doesn't exist";
            $method = $callback[1];
            if(!method_exists($className,$method)) echo "Method doesn't exist";
            $classInstance = new $className();
            echo json_encode($classInstance->$method());
            self::$matched = true;
         }


    }



    /**
     * function matchRoutes checks if the matched flag is false
     * if false 404 not found and response code are sent.
     * note that this method has to be called after all http methods
     */
    public static function matchRoutes()
    {
        if (!(self::$matched)) {
            http_response_code(404); 
            echo "404 Not Found";
        }
        self::$matched = false;
    }
}
