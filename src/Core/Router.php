<?php

namespace App\Core;

header('Content-Type: application/json; charset=utf-8');
/**
 * Router class for handling HTTP route matching and callbacks.
 */
class Router
{
    /**
     * Flag to track whether a route has been matched.
     *
     * @var bool
     */
    private static $matched = false;

    /**
     * Matches an HTTP route based on the request method and URI and calls the callback.
     *
     * @param string $httpMethod The HTTP method (e.g., "GET", "POST").
     * @param string $httpUri    The URI to match against.
     * @param array  $callback   An array containing the class name and method name to call.
     */
    public static function http($httpMethod, $httpUri, $callback)
    {
        try {
            // Get the request URI
            $requestUri = $_SERVER['REQUEST_URI'];
            $requestUri = explode('?', $requestUri)[0];

            // Compare HTTP method and $requestUri
            if (
                $requestUri === $httpUri
                && $_SERVER['REQUEST_METHOD'] == strtoupper($httpMethod)
            ) {
                // Check if the class exists
                $className = 'App\\Controllers\\' . $callback[0];
                if (!class_exists($className)){
                    die(json_encode("Class doesn't exist"));
                }

                // Check if the method exists
                $method = $callback[1];
                if (!method_exists($className, $method)){
                    die(json_encode("Method doesn't exist"));
                }
                
                // Instantiate the class and call the method
                $classInstance = new $className();
                $response = $classInstance->$method();
                if (!($response === null))
                    echo json_encode($response);
                self::$matched = true;
                die();
            }
        } catch (\Exception $e) {
            die(
                json_encode(
                    [
                        "Code" => $e->getCode(),
                        "Message" => $e->getMessage()
                    ]
                )
            );
        }
    }

    /**
     * Checks if any route has been matched, and if not, sets the response code to 404.
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
