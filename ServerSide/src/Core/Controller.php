<?php

namespace App\Core;

/**
 * Base controller class for common controller functionality.
 */
class Controller 
{
    /**
     * Retrieve and parse the JSON request body.
     *
     * @return array|null Parsed JSON data or null if parsing fails.
     */
    protected function requestBody()
    {
        $postData = file_get_contents("php://input");
        $jsonData = json_decode($postData, true);
        return $jsonData;
    }
}
