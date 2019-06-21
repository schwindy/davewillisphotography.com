<?php
$obj = new Element($db->get_row("SELECT * FROM elements WHERE id='$_REQUEST[id]'"));
if (empty($obj)) {
    redirect_to('/admin/kek/elements');
}
?>
<div class="card width_50 margin_0 float_left">
    <form id="edit_element" class="kek_form width_100 margin_auto_x">
        <input id="id" name="id" type="hidden" value="<?php echo $obj->id ?>">

        <p class="bold text_left">Element Name</p>
        <input id="display_name" name="display_name" placeholder="Element Name"
               value="<?php echo $obj->display_name ?>">

        <p class="bold text_left">Element Type</p>
        <input id="type" name="type" placeholder="Element Type" value="<?php echo $obj->type ?>">

        <p class="bold text_left">Notes</p>
        <textarea id="notes" name="notes" placeholder="Notes (Admin Only)"><?php echo $obj->notes ?></textarea>
    </form>

    <h3 class="bold text_left">Options</h3>
    <?php echo generate_options_element($obj) ?>

    <h3 class="bold text_left">Properties</h3>
    <?php echo generate_properties_element($obj) ?>
    <?php include(WEBROOT . 'js/kek/elements/edit.php'); ?>
</div>
<div id="preview_container" class="width_45 padding_sm margin_0 float_left">
    <h2 class="bold text_center">Element Preview</h2>
    <div id="preview" class="card"><?php echo __kek_decode($obj->data, $obj) ?></div>
</div>

<div class="ide_container width_100 float_left">
    <p class="bold text_left margin_xsm_left element_data">Element Data</p>
    <div id="ide" class="ide">
        <div class="line_numbers"><span>1</span></div>
        <textarea
                id="data"
                name="data"
                class="height_50 width_100"
                placeholder="Element Data"
        ><?php echo br2nl($obj->data), $obj ?></textarea>
    </div>
</div>
