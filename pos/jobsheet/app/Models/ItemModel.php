<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    use HasFactory;
    
    protected $table = 'items'; // Explicitly set the table name
    protected $fillable = ['item_type_id', 'name', 'price', 'stock'];
    
    public function itemType()
    {
        return $this->belongsTo(ItemTypeModel::class, 'item_type_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItemModel::class, 'item_id');
    }
}