<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'empresa' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => '('.fake()->numberBetween(11, 99).') 9'.fake()->numerify('####-####'),
            'area_atuacao' => fake()->randomElement(config('quiz.areas_atuacao')),
            'origem' => config('quiz.origem'),
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'consentimento_lgpd' => true,
        ];
    }
}
