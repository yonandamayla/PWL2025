<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;
    
    protected $table = 'orders'; // Explicitly set the table name
    protected $fillable = ['user_id', 'total_price', 'discount', 'status', 'order_date'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}