<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    protected $table = "suppliers";

    public function layups()
    {
        return $this->hasMany(CLT_Layup::class, "supplier_id");
    }
}
