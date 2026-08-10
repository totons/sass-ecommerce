<?php
    $cartContent = $cartContent ?? collect();
    $subtotal = isset($subtotal) ? (float) $subtotal : (float) str_replace(',', '', Cart::instance('shopping')->subtotal());
?>
<?php if($cartContent->isEmpty()): ?>
    <div class="header-cart-hover-empty">
        <p class="mb-0">কার্ট খালি</p>
        <a href="<?php echo e(route('shop')); ?>" class="header-cart-hover-checkout">শপিং করুন</a>
    </div>
<?php else: ?>
    <div class="header-cart-hover-items">
        <?php $__currentLoopData = $cartContent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="header-cart-hover-row">
                <a href="<?php echo e(route('product', $item->options->slug ?? '#')); ?>" class="header-cart-hover-thumb">
                    <img src="<?php echo e(asset($item->options->image ?? 'public/uploads/default.webp')); ?>" alt="">
                </a>
                <div class="header-cart-hover-info">
                    <a href="<?php echo e(route('product', $item->options->slug ?? '#')); ?>" class="header-cart-hover-name"><?php echo e(Str::limit($item->name, 42)); ?></a>
                    <div class="header-cart-hover-qty">পরিমাণ: <?php echo e($item->qty); ?></div>
                    <div class="header-cart-hover-price-row">
                        <span class="header-cart-hover-price">৳ <?php echo e(number_format((float) $item->price, 0)); ?></span>
                        <button type="button" class="header-cart-hover-remove cart_remove" data-id="<?php echo e($item->rowId); ?>" title="সরান">×</button>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="header-cart-hover-footer">
        <p class="header-cart-hover-total-line">সর্বমোট: <strong>৳ <?php echo e(number_format($subtotal, 0)); ?></strong></p>
        <a href="<?php echo e(route('customer.checkout')); ?>" class="header-cart-hover-checkout">অর্ডার করুন</a>
    </div>
<?php endif; ?>
<?php /**PATH C:\dms office\pos ecommerce\resources\views/frontEnd/layouts/ajax/header_cart_hover.blade.php ENDPATH**/ ?>