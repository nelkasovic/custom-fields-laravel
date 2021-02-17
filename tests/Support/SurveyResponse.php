<?php

namespace Wimando\Tests\Support;

use Wimando\LaravelCustomFields\Traits\HasCustomFieldResponses;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasCustomFieldResponses;
}
