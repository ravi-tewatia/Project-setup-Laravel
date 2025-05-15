<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;
    protected $table = "user_tokens";
    protected $primaryKey = 'user_token_id';
    public $refTableId = 13;
    protected $fillable = [
        "slug", "user_id", "token", "token_type", "token_validity", "created_at", "created_by",
    ];
}
