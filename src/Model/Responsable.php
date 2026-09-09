<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    protected $table = 'responsables';

    protected $fillable = [
        'nom',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}