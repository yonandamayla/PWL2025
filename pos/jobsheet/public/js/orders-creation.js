// Initialize cart data
let cart = JSON.parse(localStorage.getItem('blueposCart')) || [];
let products = [];

$(document).ready(function() {
    // Load products
    loadProducts();
    
    // Update cart UI
    updateCartBadge();
    
    // Filter event handlers
    $('#category-filter').change(function() {
        filterProducts();
    });
    
    $('#sort-filter').change(function() {
        filterProducts();
    });
    
    $('#search-input').on('keyup', function() {
        filterProducts();
    });
    
    // Show cart modal
    $('#showCart').click(function() {
        renderCartItems();
        $('#cartModal').modal('show');
    });
    
    // Clear cart
    $('#clearCart').click(function() {
        Swal.fire({
            title: 'Hapus semua item?',
            text: "Anda yakin ingin mengosongkan keranjang belanja?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus Semua',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                saveCart();
                updateCartBadge();
                renderCartItems();
                
                Swal.fire(
                    'Terhapus!',
                    'Keranjang belanja telah dikosongkan.',
                    'success'
                );
            }
        });
    });
    
    // Checkout button
    $('#checkoutBtn').click(function() {
        if (cart.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Keranjang Kosong',
                text: 'Silahkan tambahkan barang ke keranjang terlebih dahulu'
            });
            return;
        }
        
        renderCheckoutItems();
        $('#cartModal').modal('hide');
        $('#checkoutModal').modal('show');
    });
    
    // Place order button
    $('#placeOrderBtn').click(function() {
        let paymentMethod = $('input[name="payment_method"]:checked').val();
        
        // Update payment modal based on selected method
        updatePaymentModal(paymentMethod);
        
        $('#checkoutModal').modal('hide');
        $('#paymentModal').modal('show');
    });
    
    // Change payment method
    $('input[name="payment_method"]').change(function() {
        let method = $(this).val();
        
        // Hide all payment options
        $('.payment-option').hide();
        
        // Show selected payment option
        $(`#payment-${method}`).show();
    });
    
    // Cash amount input change
    $('#cash-amount').on('input', function() {
        let cashAmount = parseFloat($(this).val()) || 0;
        let total = calculateTotal();
        
        if (cashAmount >= total) {
            let change = cashAmount - total;
            $('#cash-change').text('Rp ' + numberFormat(change));
            $('#change-container').show();
            $('#confirmPaymentBtn').prop('disabled', false);
        } else {
            $('#change-container').hide();
            $('#confirmPaymentBtn').prop('disabled', true);
        }
    });
    
    // Confirm payment button
    $('#confirmPaymentBtn').click(function() {
        let paymentMethod = $('input[name="payment_method"]:checked').val();
        
        // Show loading state
        $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
        $(this).prop('disabled', true);
        
        // Prepare order items data
        const orderItems = cart.map(item => ({
            id: item.id,
            quantity: item.quantity
        }));
        
        // Submit order to server
        $.ajax({
            url: PLACE_ORDER_ROUTE,
            method: 'POST',
            data: {
                items: orderItems,
                payment_method: paymentMethod,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Clear cart
                    cart = [];
                    saveCart();
                    updateCartBadge();
                    
                    // Show order number
                    $('#order-number').text(response.order_number || 'ORD-' + response.order_id);
                    
                    // Hide payment modal and show success modal
                    $('#paymentModal').modal('hide');
                    $('#successModal').modal('show');
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan, silakan coba lagi';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Membuat Pesanan',
                    text: message
                });
                
                // Reset button state
                $('#confirmPaymentBtn').html('Konfirmasi Pembayaran');
                $('#confirmPaymentBtn').prop('disabled', false);
            }
        });
    });
    
    // New order button
    $('#newOrderBtn').click(function() {
        location.reload();
    });
    
    // Product cards event delegation for Add to Cart
    $(document).on('click', '.add-to-cart-btn', function() {
        const productId = $(this).data('id');
        addToCart(productId, 1);
        
        // Animate the cart button
        $('#showCart').addClass('animate__animated animate__rubberBand');
        setTimeout(function() {
            $('#showCart').removeClass('animate__animated animate__rubberBand');
        }, 1000);
    });
    
    // Cart quantity controls event delegation
    $(document).on('click', '.quantity-decrease', function() {
        const productId = $(this).data('id');
        updateCartItemQuantity(productId, -1);
    });
    
    $(document).on('click', '.quantity-increase', function() {
        const productId = $(this).data('id');
        updateCartItemQuantity(productId, 1);
    });
    
    $(document).on('change', '.quantity-input', function() {
        const productId = $(this).data('id');
        const quantity = parseInt($(this).val()) || 1;
        
        if (quantity < 1) {
            $(this).val(1);
            updateCartItemQuantity(productId, 0, 1);
        } else {
            updateCartItemQuantity(productId, 0, quantity);
        }
    });
    
    // Remove item from cart
    $(document).on('click', '.remove-cart-item', function() {
        const productId = $(this).data('id');
        removeFromCart(productId);
    });
});

// Load products
function loadProducts(category = '', sort = '', search = '') {
    $('#products-container').html(`
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    `);
    
    $.ajax({
        url: ITEMS_ROUTE,
        method: 'GET',
        data: {
            category_id: category,
            sort: sort,
            search: search
        },
        success: function(response) {
            products = response.data;
            renderProducts(products);
        },
        error: function() {
            $('#products-container').html(`
                <div class="col-12 text-center py-5">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-2"></i> Gagal memuat daftar produk
                    </div>
                </div>
            `);
        }
    });
}

// Filter products
function filterProducts() {
    const category = $('#category-filter').val();
    const sortBy = $('#sort-filter').val();
    const search = $('#search-input').val();
    
    loadProducts(category, sortBy, search);
}

// Render products
function renderProducts(products) {
    if (!products || products.length === 0) {
        $('#products-container').html(`
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i> Tidak ada produk yang ditemukan
                </div>
            </div>
        `);
        return;
    }
    
    let html = '';
    
    products.forEach(product => {
        let stockBadge = '';
        if (product.stock <= 0) {
            stockBadge = '<span class="badge badge-danger product-badge">Habis</span>';
        } else if (product.stock < 10) {
            stockBadge = '<span class="badge badge-warning product-badge">Stok Terbatas</span>';
        }
        
        html += `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card product-card">
                <div class="product-img-container">
                    ${stockBadge}
                    <img src="${product.image_url}" class="product-img" alt="${product.name}" 
                        onerror="this.onerror=null;this.src='/images/no-image.png';">
                </div>
                    <div class="product-body">
                        <h5 class="product-title">${product.name}</h5>
                        <p class="product-category">${product.category_name || 'Uncategorized'}</p>
                        <div class="product-price">Rp ${numberFormat(product.price)}</div>
                        <div class="product-stock">
                            Stok: 
                            <span class="${product.stock <= 0 ? 'text-danger' : product.stock < 10 ? 'text-warning' : 'text-success'}">
                                ${product.stock}
                            </span>
                        </div>
                        <button class="btn ${product.stock <= 0 ? 'btn-secondary' : 'btn-primary'} btn-sm btn-block add-to-cart-btn" 
                            ${product.stock <= 0 ? 'disabled' : ''} 
                            data-id="${product.id}">
                            <i class="fas ${product.stock <= 0 ? 'fa-ban' : 'fa-cart-plus'} mr-1"></i> 
                            ${product.stock <= 0 ? 'Stok Habis' : 'Tambah ke Keranjang'}
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#products-container').html(html);
}

// Add to cart function
function addToCart(productId, quantity) {
    const product = products.find(p => p.id == productId);
    
    if (!product) return;
    
    // Check if product is in stock
    if (product.stock <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Stok Habis',
            text: 'Maaf, produk ini sedang tidak tersedia'
        });
        return;
    }
    
    // Check if item exists in cart
    const existingItem = cart.find(item => item.id == productId);
    
    if (existingItem) {
        // Check if requested quantity is available
        if (existingItem.quantity + quantity > product.stock) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Tidak Cukup',
                text: `Hanya tersedia ${product.stock} item`
            });
            return;
        }
        
        existingItem.quantity += quantity;
    } else {
        // Add new item to cart
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image_url: product.image_url,
            quantity: quantity,
            stock: product.stock
        });
    }
    
    // Save cart to local storage
    saveCart();
    
    // Update UI
    updateCartBadge();
    
    // Show notification
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    
    Toast.fire({
        icon: 'success',
        title: 'Produk ditambahkan ke keranjang'
    });
}

// Update cart item quantity
function updateCartItemQuantity(productId, change, absoluteValue = null) {
    const index = cart.findIndex(item => item.id == productId);
    
    if (index === -1) return;
    
    // If absoluteValue is provided, set quantity to that value
    if (absoluteValue !== null) {
        cart[index].quantity = absoluteValue;
    } else {
        // Otherwise adjust quantity by change amount
        cart[index].quantity += change;
    }
    
    // Make sure quantity doesn't go below 1
    if (cart[index].quantity < 1) {
        cart[index].quantity = 1;
    }
    
    // Make sure quantity doesn't exceed stock
    if (cart[index].quantity > cart[index].stock) {
        cart[index].quantity = cart[index].stock;
        
        Swal.fire({
            icon: 'error',
            title: 'Stok Tidak Cukup',
            text: `Hanya tersedia ${cart[index].stock} item`
        });
    }
    
    // Save cart to local storage
    saveCart();
    
    // Update UI
    renderCartItems();
    updateCartBadge();
}

// Remove item from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id != productId);
    
    // Save cart to local storage
    saveCart();
    
    // Update UI
    renderCartItems();
    updateCartBadge();
}

// Save cart to local storage
function saveCart() {
    localStorage.setItem('blueposCart', JSON.stringify(cart));
}

// Update cart badge
function updateCartBadge() {
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    $('#cartBadge').text(totalItems);
}

// Render cart items
function renderCartItems() {
    if (cart.length === 0) {
        $('#cart-items').hide();
        $('#empty-cart').show();
        $('#checkoutBtn').prop('disabled', true);
        $('#clearCart').prop('disabled', true);
        $('#cartTotal').text('Rp 0');
        return;
    }
    
    let html = '';
    let total = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        html += `
            <div class="cart-item">
                <div class="row align-items-center">
                    <div class="col-2">
                        <img src="${item.image_url}" class="cart-item-img" alt="${item.name}"
                            onerror="this.onerror=null;this.src='/images/no-image.png';">
                    </div>
                    <div class="col-4">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">Rp ${numberFormat(item.price)}</div>
                    </div>
                    <div class="col-4">
                        <div class="cart-item-quantity">
                            <button class="btn btn-sm btn-outline-secondary quantity-btn quantity-decrease" data-id="${item.id}">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="${item.stock}" data-id="${item.id}">
                            <button class="btn btn-sm btn-outline-secondary quantity-btn quantity-increase" data-id="${item.id}">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <div class="cart-item-subtotal">Rp ${numberFormat(itemTotal)}</div>
                    </div>
                    <div class="col-1 text-right">
                        <button class="btn btn-sm btn-outline-danger remove-cart-item" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#cart-items').html(html).show();
    $('#empty-cart').hide();
    $('#checkoutBtn').prop('disabled', false);
    $('#clearCart').prop('disabled', false);
    $('#cartTotal').text('Rp ' + numberFormat(total));
}

// Render checkout items
function renderCheckoutItems() {
    if (cart.length === 0) return;
    
    let html = '';
    let subtotal = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        html += `
            <div class="checkout-item">
                <div class="row">
                    <div class="col-7">
                        <div class="font-weight-bold">${item.name}</div>
                        <div class="small text-muted">Rp ${numberFormat(item.price)} × ${item.quantity}</div>
                    </div>
                    <div class="col-5 text-right">
                        Rp ${numberFormat(itemTotal)}
                    </div>
                </div>
            </div>
        `;
    });
    
    // No discount for now
    const discount = 0;
    const total = subtotal - discount;
    
    $('#checkout-items').html(html);
    $('#checkout-subtotal').text('Rp ' + numberFormat(subtotal));
    $('#checkout-discount').text('Rp ' + numberFormat(discount));
    $('#checkout-total').text('Rp ' + numberFormat(total));
}

// Update payment modal
function updatePaymentModal(method) {
    // Hide all payment options
    $('.payment-option').hide();
    
    // Show selected payment option
    $(`#payment-${method}`).show();
    
    // Update payment amounts
    const total = calculateTotal();
    $('#cash-total').text('Rp ' + numberFormat(total));
    $('#transfer-total').text('Rp ' + numberFormat(total));
    $('#ewallet-total').text('Rp ' + numberFormat(total));
    
    // Reset fields
    $('#cash-amount').val('');
    $('#change-container').hide();
    $('#transfer-proof').val('');
    
    // Enable/disable confirm button based on payment method
    if (method === 'cash') {
        $('#confirmPaymentBtn').prop('disabled', true);
    } else {
        $('#confirmPaymentBtn').prop('disabled', false);
    }
}

// Calculate total
function calculateTotal() {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

// Format number to currency
function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}