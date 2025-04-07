<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTypeModel extends Model
{
    use HasFactory;
    
    protected $table = 'item_types'; // Explicitly set the table name
    protected $fillable = ['name', 'description'];
    
    public function items()
    {
        return $this->hasMany(ItemModel::class, 'item_type_id');
    }
}