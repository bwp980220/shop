<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'address';
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $primaryKey = 'address_id';
}
