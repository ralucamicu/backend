<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiResponse extends Model
{
    protected $table = 'api_responses';
     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = [
        'result', 
        'type',
        'name',
    ];
    use HasFactory;
}
