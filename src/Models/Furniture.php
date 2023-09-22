<?php

namespace App\Models;

use App\Core\Model;

class Furniture extends Model
{
    /**
     * @var int 
     */
    public $product_id;

    /**
     * @var float 
     */
    public $dimension_l;

    /**
     * @var float 
     */
    public $dimension_h;

    /**
     * @var float 
     */
    public $dimension_w;
}
