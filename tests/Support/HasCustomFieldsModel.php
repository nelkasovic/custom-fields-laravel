<?php

namespace Wimando\Tests\Support;

use Wimando\LaravelCustomFields\Traits\HasCustomFields;
use Illuminate\Database\Eloquent\Model;

class HasCustomFieldsModel extends Model
{
    use HasCustomFields;
}
