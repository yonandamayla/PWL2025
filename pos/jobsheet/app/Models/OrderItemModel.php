<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemModel extends Model
{
    use HasFactory;
    
    protected $table = 'order_items'; // Explicitly set the table name
    protected $fillable = ['order_id', 'item_id', 'quantity', 'subtotal'];
    
    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }
    
    public function item()
    {
        return $this->belongsTo(ItemModel::class, 'item_id');
    }
}