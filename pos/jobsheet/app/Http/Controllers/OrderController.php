<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ItemModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display transaction list, details or receipt based on parameters
     */
    public function index(Request $request)
    {
        // Get the order_id from the query parameters
        $order_id = $request->query('order_id');
        $view = $request->query('view');
        
        // If order_id is provided, fetch the order details
        if ($order_id) {
            $order = OrderModel::findOrFail($order_id);
            // Generate an order number (or retrieve it from the database)
            $orderNumber = 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
            
            return view('orders.index', compact('order', 'order_id', 'orderNumber', 'view'));
        }
        
        // Otherwise, just show the orders list view
        return view('orders.index');
    }

    /**
     * Get orders data for DataTables
     */
    public function getOrders(Request $request)
    {
        $query = OrderModel::with(['user', 'orderItems.item']);
        
        // Apply filters if present
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter for customer - only show their own orders
        if (Auth::user()->role_id == 3) {
            $query->where('user_id', Auth::id());
        }
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('customer_name', function($order) {
                return $order->user->name;
            })
            ->addColumn('order_number', function($order) {
                // Generate order number on-the-fly based on ID
                return 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            })
            ->addColumn('items_count', function($order) {
                return $order->orderItems->sum('quantity');
            })
            ->addColumn('total_formatted', function($order) {
                $total = $order->total_price - ($order->total_price * $order->discount / 100);
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('status_label', function($order) {
                $badges = [
                    'pending' => '<span class="badge badge-warning">Menunggu</span>',
                    'processing' => '<span class="badge badge-info">Diproses</span>',
                    'completed' => '<span class="badge badge-success">Selesai</span>',
                    'cancelled' => '<span class="badge badge-danger">Dibatalkan</span>',
                ];
                return $badges[$order->status] ?? $order->status;
            })
            ->addColumn('date_formatted', function($order) {
                return Carbon::parse($order->order_date ?? $order->created_at)->format('d M Y H:i');
            })
            ->addColumn('actions', function($order) {
                $actions = '<div class="btn-group">';
                $actions .= '<a href="'.route('orders.index', ['order_id' => $order->id]).'" class="btn btn-sm btn-info" title="Lihat Detail"><i class="fas fa-eye"></i></a>';
                $actions .= '<a href="'.route('orders.index', ['view' => 'receipt', 'order_id' => $order->id]).'" class="btn btn-sm btn-secondary" title="Cetak Struk" target="_blank"><i class="fas fa-print"></i></a>';
                
                // Show process button only for pending orders to admin/cashier
                if ($order->status == 'pending' && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)) {
                    $actions .= '<button class="btn btn-sm btn-primary process-btn" data-id="'.$order->id.'" title="Proses Pesanan"><i class="fas fa-tasks"></i></button>';
                }
                
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['status_label', 'actions'])
            ->make(true);
    }

    /**
     * Process a pending order - simply updates status
     */
    public function processOrder(Request $request, $id)
    {
        $order = OrderModel::findOrFail($id);
        
        // Only process pending orders
        if ($order->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pesanan dengan status menunggu yang dapat diproses'
            ], 400);
        }
        
        $order->status = 'processing';
        // Store the processor information in status itself since we don't have a dedicated field
        $order->status = 'processing:' . Auth::id(); // Will be parsed when needed
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Pesanan sedang diproses'
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,completed,cancelled',
        ]);
        
        $order = OrderModel::findOrFail($id);
        
        // Don't update if already completed or cancelled
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan yang sudah selesai atau dibatalkan tidak dapat diubah'
            ], 400);
        }
        
        // If cancelling, restore stock
        if ($validated['status'] == 'cancelled' && $order->status != 'cancelled') {
            $this->restoreStock($order);
        }
        
        $order->status = $validated['status'];
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui'
        ]);
    }
    
    /**
     * Restore stock when order is cancelled
     */
    private function restoreStock($order)
    {
        foreach ($order->orderItems as $orderItem) {
            $item = ItemModel::find($orderItem->item_id);
            if ($item) {
                $item->stock += $orderItem->quantity;
                $item->save();
            }
        }
    }
    
    /**
     * Show order creation form for customers
     */
    public function create()
    {
        // Check if user is customer
        if (Auth::user()->role_id != 3) {
            return redirect()->route('orders.index');
        }
        
        $activeMenu = 'create-order';
        $items = ItemModel::where('stock', '>', 0)->get();
        
        return view('orders.create', compact('activeMenu', 'items'));
    }
    
    /**
     * Store a new order (for customers)
     */
    public function store(Request $request)
    {
        // Check if user is customer
        if (Auth::user()->role_id != 3) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya customer yang dapat membuat pesanan'
            ], 403);
        }
        
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create the order
            $order = new OrderModel();
            $order->user_id = Auth::id();
            $order->total_price = 0;
            $order->discount = 0; // Can be adjusted based on business rules
            $order->status = 'pending';
            $order->order_date = now();
            $order->save();
            
            $totalPrice = 0;
            
            // Process each order item
            foreach ($validated['items'] as $itemData) {
                $item = ItemModel::findOrFail($itemData['id']);
                
                if ($item->stock < $itemData['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk item {$item->name}");
                }
                
                // Create order item
                $orderItem = new OrderItemModel();
                $orderItem->order_id = $order->id;
                $orderItem->item_id = $item->id;
                $orderItem->quantity = $itemData['quantity'];
                $orderItem->subtotal = $item->price * $itemData['quantity'];
                $orderItem->save();
                
                // Update stock
                $item->stock -= $itemData['quantity'];
                $item->save();
                
                $totalPrice += $orderItem->subtotal;
            }
            
            // Update order total
            $order->total_price = $totalPrice;
            $order->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'order_id' => $order->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}