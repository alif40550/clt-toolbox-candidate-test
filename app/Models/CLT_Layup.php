<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CLT_Layup extends Model
{
    /** @use HasFactory<\Database\Factories\CLTLayupFactory> */
    use HasFactory;

    protected $table="clt_layups";
    protected $fillable = ['name'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, "supplier_id");
    }

    public function layers()
    {
        return $this->hasMany(CLT_Layer::class, "layup_id");
    }
}
