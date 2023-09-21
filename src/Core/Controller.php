<?php 

namespace App\Core;

 class Controller 
 {
    /**
     * get request body.
     */
    protected function requestBody()
    {
      $postData = file_get_contents("php://input");
      $jsonData = json_decode($postData ,true);
      return $jsonData;
    }
 }