<?php

namespace Wimando\Tests\Feature;

use Wimando\LaravelCustomFields\Models\CustomField;
use Wimando\LaravelCustomFields\Models\CustomFieldResponse;
use Wimando\Tests\Support\Survey;
use Wimando\Tests\Support\SurveyResponse;
use Wimando\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasCustomFieldResponsesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function custom_fields_responses_can_be_created_and_accessed_on_models_with_trait()
    {
        $customFieldModel = Survey::create();
        $customFieldResponseModel = SurveyResponse::create();

        $customField = factory(CustomField::class)->make([
            'model_id' => $customFieldModel->id,
            'model_type' => get_class($customFieldModel),
        ]);

        $customFieldModel->customFields()->save($customField);

        $customFieldResponse = CustomFieldResponse::make([
            'model_id' => $customFieldResponseModel->id,
            'model_type' => get_class($customFieldResponseModel),
            'field_id' => $customField->fresh()->id,
            'value_str' => 'Best Rapper Alive',
        ]);

        $customFieldResponseModel->customFieldResponses()->save($customFieldResponse);

        $this->assertCount(1, $customFieldResponseModel->fresh()->customFieldResponses);
        $this->assertEquals('Best Rapper Alive', $customFieldResponseModel->fresh()->customFieldResponses->first()->value_str);
    }

    /** @test */
    public function whereField_method_allows_filtering_responses()
    {
        $customFieldModel = Survey::create();
        $firstResponseModel = SurveyResponse::create();
        $secondResponseModel = SurveyResponse::create();

        $firstField = factory(CustomField::class)->create([
            'model_id' => $customFieldModel->id,
            'model_type' => get_class($customFieldModel),
        ]);

        $firstResponse = CustomFieldResponse::create([
            'model_id' => $firstResponseModel->id,
            'model_type' => get_class($firstResponseModel),
            'field_id' => $firstField->id,
            'value_str' => 'Hit Em Up',
        ]);

        $secondResponse = CustomFieldResponse::create([
            'model_id' => $secondResponseModel->id,
            'model_type' => get_class($secondResponseModel),
            'field_id' => $firstField->id,
            'value_str' => 'Best Rapper Alive',
        ]);

        $firstResponseModel->customFieldResponses()->save($firstResponse);
        $secondResponseModel->customFieldResponses()->save($secondResponse);

        $this->assertCount(1, SurveyResponse::whereField($firstField, 'Hit Em Up')->get());
        $this->assertEquals($firstResponse->id, SurveyResponse::whereField($firstField, 'Hit Em Up')->first()->id);

        $this->assertCount(1, SurveyResponse::whereField($firstField, 'Best Rapper Alive')->get());
        $this->assertEquals($secondResponse->id, SurveyResponse::whereField($firstField, 'Best Rapper Alive')->first()->id);
    }


    /** @test */
    public function value_getter_and_setter_work_fine()
    {
        $customFieldModel = Survey::create();
        $customFieldResponseModel = SurveyResponse::create();

        $customField = factory(CustomField::class)->make([
            'model_id' => $customFieldModel->id,
            'model_type' => get_class($customFieldModel),
            'type' => 'text',
        ]);

        $customFieldModel->customFields()->save($customField);

        $customFieldResponse = CustomFieldResponse::make([
            'model_id' => $customFieldResponseModel->id,
            'model_type' => get_class($customFieldResponseModel),
            'field_id' => $customField->fresh()->id,
            'value_str' => 'Best Rapper Alive',
        ]);

        $customFieldResponseModel->customFieldResponses()->save($customFieldResponse);
        $this->assertEquals('Best Rapper Alive', $customFieldResponse->fresh()->value);
        $customFieldResponse->update(['value' => 'Hit Em Up']);
        $this->assertEquals('Hit Em Up', $customFieldResponse->fresh()->value);
    }
}
