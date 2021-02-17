<?php

namespace Wimando\Tests\Support;

use Wimando\LaravelCustomFields\Traits\HasCustomFields;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasCustomFields;
}
