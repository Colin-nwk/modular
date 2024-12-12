<?php

namespace Modules\Foobar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foobar extends Model
{
    /** @use HasFactory<\Database\Factories\FoobarFactory> */
    use HasFactory;
}
