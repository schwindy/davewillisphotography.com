<br>
<?php
echo __html('card', [
    'text' => __html('h1', "Your Shopping Cart") . __html('br') . "<div id='cart' class='width_100'></div>"
]);
?>

<div class="card cart_info">
    <h4>Subtotal: $<span id="subtotal"></span></h4>
    <h4>Processing: $<span id="processing"><?php echo __config('SHOP_PROCESSING_FEE') ?></span></h4>
    <br>
    <h2>Grand Total: $<span id="total"></span></h2>
</div>

<a href='/checkout' class='width_90 display_block margin_auto_x padding_sm_y text_center blue_bg'>
    <img src='/img/icons/input_icon.png'>
    <h2 class='white bold'>Checkout</h2>
</a>

<script>$(function(){
        get_cart_html()
    });</script>