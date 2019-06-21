<?php
if (empty($_REQUEST['id'])) {
    redirect_to($route->home);
}
$payment = new Payment($_REQUEST['id']);
?>
<div class="card text_center">
    <a href="/"><img class="text_center" src="/img/logo/circle_icon_sm.png"></a>
    <h1>Payment</h1>
    <h4>ID: <?php echo $payment->id ?></h4>
    <br>
    <h3>Status: <?php echo ucfirst($payment->status) ?></h3>
    <br>
    <p>Please send <b><?php echo "$payment->cost $payment->currency_id" ?></b></p>
    <p>To Deposit Address: <b><?php echo $payment->deposit_address ?></b></p>
    <br>
    <p>This page will automatically refresh if your Payment Status changes.</p>
    <br>
    <p>Thank you for using <?php echo SITE_NAME ?></p>
</div>