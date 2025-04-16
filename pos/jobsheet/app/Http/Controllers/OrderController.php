<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ItemModel;
use App\Models\UserModel;
use App\Models\ItemTypeModel;
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
        $isCustomer = Auth::user()->role_id == 3;
        $isAdminOrCashier = Auth::user()->role_id == 1 || Auth::user()->role_id == 2;

        // Apply filters if present
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter for customer - only show their own orders
        if ($isCustomer) {
            $query->where('user_id', Auth::id());
        }

        $dt = DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('order_number', function ($order) {
                // Generate order number on-the-fly based on ID
                return 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            })
            ->addColumn('items_count', function ($order) {
                return $order->orderItems->sum('quantity');
            })
            ->addColumn('total_formatted', function ($order) {
                $total = $order->total_price - ($order->total_price * $order->discount / 100);
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('status_label', function ($order) {
                $status = strtolower($order->status);
                
                switch ($status) {
                    case 'pending':
                        return '<span class="badge badge-warning">Menunggu</span>';
                    case 'processing':
                        return '<span class="badge badge-info">Diproses</span>';
                    case 'completed':
                        return '<span class="badge badge-success">Selesai</span>';
                    case 'cancelled':
                        return '<span class="badge badge-danger">Dibatalkan</span>';
                    default:
                        return '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
                }
            })
            ->addColumn('date_formatted', function ($order) {
                return Carbon::parse($order->order_date ?? $order->created_at)->format('d M Y H:i');
            });

        // Add customer_name column only for admin or cashier
        if ($isAdminOrCashier) {
            $dt->addColumn('customer_name', function ($order) {
                return $order->user->name;
            });
        }

        // Different action buttons for customer vs admin/cashier
        // Inside the addColumn('actions') method in getOrders function
        $dt->addColumn('actions', function ($order) use ($isAdminOrCashier, $isCustomer) {
            $actions = '<div class="btn-group">';
        
            // Detail button for all users
            $actions .= '<a href="' . route('orders.index', ['order_id' => $order->id]) . '" class="btn btn-sm btn-info" title="Lihat Detail"><i class="fas fa-eye"></i></a>';
        
            if ($isAdminOrCashier) {
                // Print receipt button for admin/cashier - ONLY for non-cancelled orders
                if ($order->status != 'cancelled') {
                    $actions .= '<a href="' . route('orders.index', ['view' => 'receipt', 'order_id' => $order->id]) . '" class="btn btn-sm btn-secondary" title="Cetak Struk" target="_blank"><i class="fas fa-print"></i></a>';
                }
        
                // Process button for pending orders (admin/cashier)
                if ($order->status == 'pending') {
                    $actions .= '<button class="btn btn-sm btn-primary process-btn" data-id="' . $order->id . '" title="Proses Pesanan"><i class="fas fa-tasks"></i></button>';
                }
            }

            if ($isCustomer) {
                // Cancel button for pending orders (customer)
                if ($order->status == 'pending') {
                    $actions .= '<button class="btn btn-sm btn-danger cancel-btn" data-id="' . $order->id . '" title="Batalkan Pesanan"><i class="fas fa-times"></i></button>';
                }

                // Mark as received button for processing orders (customer)
                if ($order->status == 'processing') {
                    $actions .= '<button class="btn btn-sm btn-success received-btn" data-id="' . $order->id . '" title="Terima Pesanan"><i class="fas fa-check"></i></button>';
                }
            }

            $actions .= '</div>';
            return $actions;
        });

        $dt->rawColumns(['status_label', 'actions']);

        return $dt->make(true);
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
     * Show the form for creating a new order (customer view)
     */
    public function create()
    {
        // Fetch all item types (categories) to populate the filter dropdown
        $itemTypes = ItemTypeModel::all();

        return view('orders.create', [
            'itemTypes' => $itemTypes
        ]);
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

    /**
     * Get available items for ordering (customer view)
     */
    public function getAvailableItems(Request $request)
    {
        $query = ItemModel::with('itemType')
            ->where('stock', '>', 0); // Only show in-stock items by default

        // Apply category filter
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('item_type_id', $request->category_id);
        }

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'name':
                default:
                    $query->orderBy('name', 'asc');
                    break;
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $items = $query->get();

        // Format the data for frontend use
        $formattedItems = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'stock' => $item->stock,
                'category_name' => $item->itemType ? $item->itemType->name : 'Uncategorized',
                'image_url' => !empty($item->photo)
                    ? asset($item->photo)
                    : asset('images/no-image.png'),
                'description' => $item->description
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedItems
        ]);
    }
}
