<?php

namespace Wimando\Tests\Support;

use Wimando\LaravelCustomFields\Traits\HasCustomFieldResponses;
use Illuminate\Database\Eloquent\Model;

class HasCustomFieldResponsesModel extends Model
{
    use HasCustomFieldResponses;
}
