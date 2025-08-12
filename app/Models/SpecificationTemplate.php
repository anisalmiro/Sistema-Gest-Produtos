<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'unit',
        'required',
        'order',
        'description',
    ];

    protected $casts = [
        'field_options' => 'array',
        'required' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relacionamento com categoria
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope para campos obrigatórios
     */
    public function scopeRequired($query)
    {
        return $query->where('required', true);
    }

    /**
     * Scope para ordenação
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Verifica se o campo é do tipo select
     */
    public function isSelectField()
    {
        return $this->field_type === 'select';
    }

    /**
     * Verifica se o campo é obrigatório
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Obtém as opções para campos select
     */
    public function getSelectOptions()
    {
        return $this->isSelectField() ? $this->field_options : [];
    }
}

