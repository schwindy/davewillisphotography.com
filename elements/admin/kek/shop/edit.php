<?php
acl_require_admin(CURRENT_PATH);
$element = new Element($db->get_row("SELECT * FROM elements WHERE id='$_REQUEST[id]'"));
?>
<br>
<div class="card">
    <form id="edit_element" class="kek_form width_100 margin_auto_x">
        <input id="id" type="hidden" value="<?php echo $element->id ?>">
        <input id="type" type="hidden" value="<?php echo $element->type ?>">

        <p class="bold text_left">Item Name</p>
        <input id="display_name" placeholder="Item Name" value="<?php echo $element->display_name ?>">

        <p class="bold text_left">Item Description</p>
        <textarea id="item_bio" placeholder="Item Description"><?php echo $element->properties['bio'] ?></textarea>

        <p class="bold text_left">Notes</p>
        <textarea id="notes" placeholder="Notes (Admin Only)"><?php echo $element->notes ?></textarea>
        <br>
    </form>
    <h3 class="bold text_left">Options</h3>
    <?php echo generate_options_element($element) ?>

    <h3 class="bold text_left">Properties</h3>
    <?php echo generate_properties_element($element, ['bio']) ?>
</div>