<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'stock', 
        'photo',
        'item_type_id'
    ];
    
    /**
     * Get the item type that owns this item
     */
    public function itemType()
    {
        return $this->belongsTo(ItemTypeModel::class, 'item_type_id');
    }
    
    /**
     * Get the order items for this item
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItemModel::class, 'item_id');
    }
    
    /**
     * Check if the item is in stock
     */
    public function inStock()
    {
        return $this->stock > 0;
    }
    
    /**
     * Check if the item is low in stock
     */
    public function lowStock()
    {
        return $this->stock > 0 && $this->stock < 10;
    }
}