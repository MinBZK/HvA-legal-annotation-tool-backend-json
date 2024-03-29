<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MatterRelationEnum;
use App\Models\Matter;
use App\Models\MatterRelation;
use App\Models\MatterRelationSchema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MatterRelation>
 */
class MatterRelationFactory extends Factory
{
    protected $model = MatterRelation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'related_matter_id'         => Matter::factory(),
            'relation'                  => MatterRelationEnum::getRandomValue(),
            'matter_relation_schema_id' => MatterRelationSchema::factory(),
            'description'               => fake()->sentence(),
        ];
    }
}
