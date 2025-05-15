<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefTable extends Model
{
    use HasFactory;
    protected $table = "ref_table";
    protected $primaryKey = 'ref_table_id';
    protected $fillable = [
        "ref_table_id", "ref_table",
    ];
}
