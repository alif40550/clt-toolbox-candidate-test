<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CLT_Layer extends Model
{
    /** @use HasFactory<\Database\Factories\CLTLayerFactory> */
    use HasFactory;

    protected $table = "clt_layers";

    public function layup()
    {
        return $this->belongsTo(CLT_Layup::class, "layup_id");
    }
}
