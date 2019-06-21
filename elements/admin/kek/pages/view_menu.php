<div class="menu_nav_container">
    <a
            id='menu_nav_1'
            class="menu_nav_element"
            href='/admin/kek/pages/edit?id=<?php echo $_REQUEST['id'] ?>'
    >
        <img src="/img/icons/input_icon.png">
        Edit
    </a>
    <a
            id='menu_nav_2'
            class="menu_nav_element"
            href='/admin/kek/pages/copy?id=<?php echo $_REQUEST['id'] ?>'
    >
        <img src="/img/icons/cloud_circle_icon.svg">
        Copy
    </a>
    <a
            id='menu_nav_3'
            class="menu_nav_element kek_confirm_button"
            href='/admin/kek/pages/delete?id=<?php echo $_REQUEST['id'] ?>'
    >
        <img src="/img/icons/ban_icon.svg">
        Delete
    </a>
</div>
<br>
<br>