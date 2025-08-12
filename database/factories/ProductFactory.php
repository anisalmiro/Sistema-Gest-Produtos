<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Equipamentos de Laboratório',
            'Mobiliário de Escritório',
            'Equipamentos Informáticos',
            'Mobiliário de Laboratório',
            'Instrumentos de Medição'
        ];

        $specifications = [
            [
                ['key' => 'Dimensões', 'value' => $this->faker->randomElement(['100x50x75cm', '150x75x85cm', '120x60x80cm'])],
                ['key' => 'Material', 'value' => $this->faker->randomElement(['Aço Inoxidável', 'Madeira', 'Plástico', 'Metal'])],
                ['key' => 'Peso', 'value' => $this->faker->numberBetween(5, 50) . 'kg']
            ],
            [
                ['key' => 'Potência', 'value' => $this->faker->numberBetween(100, 1000) . 'W'],
                ['key' => 'Voltagem', 'value' => '220V'],
                ['key' => 'Frequência', 'value' => '50Hz']
            ],
            [
                ['key' => 'Capacidade', 'value' => $this->faker->numberBetween(10, 500) . 'L'],
                ['key' => 'Temperatura', 'value' => '-20°C a +80°C'],
                ['key' => 'Precisão', 'value' => '±0.1°C']
            ]
        ];

        return [
            'name' => $this->faker->randomElement([
                'Mesa de Laboratório',
                'Microscópio Digital',
                'Centrífuga',
                'Balança Analítica',
                'Estufa de Secagem',
                'Cadeira Ergonómica',
                'Armário de Segurança',
                'Computador Desktop'
            ]) . ' ' . $this->faker->randomElement(['Pro', 'Standard', 'Premium', 'Basic']),
            'category' => $this->faker->randomElement($categories),
            'description' => $this->faker->paragraph(2),
            'technical_specifications' => $this->faker->randomElement($specifications),
        ];
    }
}

