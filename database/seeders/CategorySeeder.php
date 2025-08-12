<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SpecificationTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Equipamentos de Laboratório
        $labEquipment = Category::create([
            'name' => 'Equipamentos de Laboratório',
            'slug' => Str::slug('Equipamentos de Laboratório'),
            'description' => 'Equipamentos científicos e de laboratório',
            'icon' => 'bi-flask',
            'active' => true,
        ]);

        // Templates para Equipamentos de Laboratório
        $labSpecs = [
            ['field_name' => 'dimensoes', 'field_label' => 'Dimensões', 'field_type' => 'text', 'unit' => 'cm', 'required' => true, 'order' => 1],
            ['field_name' => 'peso', 'field_label' => 'Peso', 'field_type' => 'number', 'unit' => 'kg', 'required' => true, 'order' => 2],
            ['field_name' => 'potencia', 'field_label' => 'Potência', 'field_type' => 'number', 'unit' => 'W', 'required' => false, 'order' => 3],
            ['field_name' => 'voltagem', 'field_label' => 'Voltagem', 'field_type' => 'select', 'field_options' => json_encode(['110V', '220V', '380V']), 'unit' => 'V', 'required' => false, 'order' => 4],
            ['field_name' => 'temperatura_operacao', 'field_label' => 'Temperatura de Operação', 'field_type' => 'text', 'unit' => '°C', 'required' => false, 'order' => 5],
            ['field_name' => 'precisao', 'field_label' => 'Precisão', 'field_type' => 'text', 'required' => false, 'order' => 6],
            ['field_name' => 'certificacoes', 'field_label' => 'Certificações', 'field_type' => 'textarea', 'required' => false, 'order' => 7],
        ];

        foreach ($labSpecs as $spec) {
            SpecificationTemplate::create(array_merge($spec, ['category_id' => $labEquipment->id]));
        }

        // Mobiliário de Escritório
        $officeFurniture = Category::create([
            'name' => 'Mobiliário de Escritório',
            'slug' => Str::slug('Mobiliário de Escritório'),
            'description' => 'Móveis e equipamentos para escritório',
            'icon' => 'bi-house',
            'active' => true,
        ]);

        // Templates para Mobiliário de Escritório
        $officeSpecs = [
            ['field_name' => 'dimensoes', 'field_label' => 'Dimensões', 'field_type' => 'text', 'unit' => 'cm', 'required' => true, 'order' => 1],
            ['field_name' => 'material', 'field_label' => 'Material', 'field_type' => 'select', 'field_options' => json_encode(['Madeira', 'Metal', 'Plástico', 'Vidro', 'MDF']), 'required' => true, 'order' => 2],
            ['field_name' => 'cor', 'field_label' => 'Cor', 'field_type' => 'text', 'required' => false, 'order' => 3],
            ['field_name' => 'peso_maximo', 'field_label' => 'Peso Máximo Suportado', 'field_type' => 'number', 'unit' => 'kg', 'required' => false, 'order' => 4],
            ['field_name' => 'ajustavel', 'field_label' => 'Ajustável', 'field_type' => 'boolean', 'required' => false, 'order' => 5],
            ['field_name' => 'garantia', 'field_label' => 'Garantia', 'field_type' => 'text', 'unit' => 'anos', 'required' => false, 'order' => 6],
        ];

        foreach ($officeSpecs as $spec) {
            SpecificationTemplate::create(array_merge($spec, ['category_id' => $officeFurniture->id]));
        }

        // Equipamentos Informáticos
        $itEquipment = Category::create([
            'name' => 'Equipamentos Informáticos',
            'slug' => Str::slug('Equipamentos Informáticos'),
            'description' => 'Computadores, periféricos e equipamentos de TI',
            'icon' => 'bi-laptop',
            'active' => true,
        ]);

        // Templates para Equipamentos Informáticos
        $itSpecs = [
            ['field_name' => 'processador', 'field_label' => 'Processador', 'field_type' => 'text', 'required' => true, 'order' => 1],
            ['field_name' => 'memoria_ram', 'field_label' => 'Memória RAM', 'field_type' => 'number', 'unit' => 'GB', 'required' => true, 'order' => 2],
            ['field_name' => 'armazenamento', 'field_label' => 'Armazenamento', 'field_type' => 'text', 'unit' => 'GB/TB', 'required' => true, 'order' => 3],
            ['field_name' => 'sistema_operativo', 'field_label' => 'Sistema Operativo', 'field_type' => 'select', 'field_options' => json_encode(['Windows 11', 'Windows 10', 'macOS', 'Linux', 'Sem SO']), 'required' => false, 'order' => 4],
            ['field_name' => 'placa_grafica', 'field_label' => 'Placa Gráfica', 'field_type' => 'text', 'required' => false, 'order' => 5],
            ['field_name' => 'conectividade', 'field_label' => 'Conectividade', 'field_type' => 'textarea', 'description' => 'USB, HDMI, WiFi, Bluetooth, etc.', 'required' => false, 'order' => 6],
            ['field_name' => 'consumo_energia', 'field_label' => 'Consumo de Energia', 'field_type' => 'number', 'unit' => 'W', 'required' => false, 'order' => 7],
        ];

        foreach ($itSpecs as $spec) {
            SpecificationTemplate::create(array_merge($spec, ['category_id' => $itEquipment->id]));
        }

        // Instrumentos de Medição
        $measurementInstruments = Category::create([
            'name' => 'Instrumentos de Medição',
            'slug' => Str::slug('Instrumentos de Medição'),
            'description' => 'Instrumentos de medição e calibração',
            'icon' => 'bi-speedometer2',
            'active' => true,
        ]);

        // Templates para Instrumentos de Medição
        $measurementSpecs = [
            ['field_name' => 'tipo_medicao', 'field_label' => 'Tipo de Medição', 'field_type' => 'select', 'field_options' => json_encode(['Temperatura', 'Pressão', 'Humidade', 'pH', 'Peso', 'Distância', 'Velocidade']), 'required' => true, 'order' => 1],
            ['field_name' => 'faixa_medicao', 'field_label' => 'Faixa de Medição', 'field_type' => 'text', 'required' => true, 'order' => 2],
            ['field_name' => 'precisao', 'field_label' => 'Precisão', 'field_type' => 'text', 'required' => true, 'order' => 3],
            ['field_name' => 'resolucao', 'field_label' => 'Resolução', 'field_type' => 'text', 'required' => false, 'order' => 4],
            ['field_name' => 'calibracao', 'field_label' => 'Certificado de Calibração', 'field_type' => 'boolean', 'required' => false, 'order' => 5],
            ['field_name' => 'interface', 'field_label' => 'Interface', 'field_type' => 'select', 'field_options' => json_encode(['Digital', 'Analógico', 'USB', 'RS232', 'Bluetooth', 'WiFi']), 'required' => false, 'order' => 6],
            ['field_name' => 'alimentacao', 'field_label' => 'Alimentação', 'field_type' => 'select', 'field_options' => json_encode(['Bateria', 'Rede Elétrica', 'USB', 'Solar']), 'required' => false, 'order' => 7],
        ];

        foreach ($measurementSpecs as $spec) {
            SpecificationTemplate::create(array_merge($spec, ['category_id' => $measurementInstruments->id]));
        }
    }
}

