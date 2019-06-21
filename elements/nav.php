<?php
echo __html('css', ['href' => '/css/nav.css']);
echo __html('css', ['href' => '/css/user.css']);
echo "<nav class='theme_color print_invisible'>";

if (!empty($_SESSION['user_id'])) {
    if (CURRENT_PAGE === 'home' || CURRENT_ROUTE === '/index') {
        echo "<a id='nav_1' class='nav_item __logout_button' href='/logout'>
            <i class='material-icons white'>exit_to_app</i>
        </a>";
    } else {
        echo "<a id='nav_1' class='nav_item __back'>
            <i class='material-icons white'>arrow_back</i>
        </a>";
    }

    echo "<a id='nav_2' class='nav_item' href='" . $route->home . "'><i class='material-icons white'>home</i></a>
    <a id='nav_3' class='nav_item' href='" . $route->account . "'><i class='material-icons white'>settings</i></a>";
} else {
    echo "<a id='nav_1' class='nav_item __back'><i class='material-icons white'>arrow_back</i></a>
    <a id='nav_2' class='nav_item' href='" . $route->home . "'><i class='material-icons white'>home</i></a>
    <a id='nav_3' class='nav_item' href='/contact'><i class='material-icons white'>email</i></a>";
}
?>
</nav>
<br>
<br>