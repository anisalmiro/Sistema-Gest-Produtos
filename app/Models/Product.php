<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'category', // Mantido para compatibilidade
        'description',
        'specifications',
        'dynamic_specifications', // Novas especificações baseadas em categoria
    ];

    protected $casts = [
        'specifications' => 'array',
        'dynamic_specifications' => 'array',
    ];

    /**
     * Relacionamento com categoria
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relacionamento com ofertas
     */
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Obtém as especificações formatadas
     */
    public function getFormattedSpecifications()
    {
        $formatted = [];
        
        // Especificações antigas (compatibilidade)
        if ($this->specifications) {
            foreach ($this->specifications as $key => $value) {
                $formatted[$key] = $value;
            }
        }
        
        // Especificações dinâmicas baseadas na categoria
        if ($this->dynamic_specifications && $this->category) {
            $templates = $this->category->specificationTemplates;
            
            foreach ($this->dynamic_specifications as $fieldName => $value) {
                $template = $templates->firstWhere('field_name', $fieldName);
                if ($template) {
                    $formatted[$template->field_label] = $value . ($template->unit ? ' ' . $template->unit : '');
                }
            }
        }
        
        return $formatted;
    }

    /**
     * Obtém o valor de uma especificação dinâmica
     */
    public function getDynamicSpecification($fieldName)
    {
        return $this->dynamic_specifications[$fieldName] ?? null;
    }

    /**
     * Define uma especificação dinâmica
     */
    public function setDynamicSpecification($fieldName, $value)
    {
        $specs = $this->dynamic_specifications ?? [];
        $specs[$fieldName] = $value;
        $this->dynamic_specifications = $specs;
    }

    /**
     * Scope para produtos de uma categoria específica
     */
    public function scopeOfCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Relacionamento com comparações de ofertas
     */
    public function offerComparisons()
    {
        return $this->hasMany(OfferComparison::class);
    }
}
