<?php
$_SESSION['last_page'] = empty($_SESSION['last_page']) ? 'home' : CURRENT_PAGE;
foreach ($_LOG as $log) {
    echo __log($log[0], $log[1]);
}
foreach ($_ALERT as $alert) {
    echo __alert($alert);
}
echo __html('css', ['href' => '/css/footer.css']);
?>

<br>
<footer class="text_center black_bg white"><p>Copyright © <?php echo date('Y') . " " . COMPANY_NAME_LONG ?></p></footer>