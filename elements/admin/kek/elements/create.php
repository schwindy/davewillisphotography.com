<br>
<div class="card">
    <form id="create_element" class="kek_form width_100 margin_auto_x">
        <p class="bold text_left">Element Name</p>
        <input id="display_name" name="display_name" placeholder="Element Name" value="<?php echo $_REQUEST['id'] ?>">

        <p class="bold text_left">Element Type</p>
        <input id="type" name="type" placeholder="Element Type" value="<?php echo $_REQUEST['type'] ?>">

        <p class="bold text_left">Element Data</p>
        <textarea id="data" name="data" placeholder="Element Data"><?php echo $_REQUEST['data'] ?></textarea>

        <p class="bold text_left">Notes</p>
        <textarea id="notes" name="notes" placeholder="Notes (Admin Only)"><?php echo $_REQUEST['notes'] ?></textarea>
    </form>
    <br>
    <h3 class="bold text_left">Options</h3>
    <?php echo generate_options_element() ?>

    <h3 class="bold text_left">Properties</h3>
    <?php echo generate_properties_element() ?>
</div>