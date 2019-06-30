<?php
acl_require_admin(CURRENT_PATH);

?>

<div class="card">
    <form
        id="file_upload_form"
        action="/php/portal?run=admin/docs/upload_file"
        method="POST"
        enctype="multipart/form-data"
        style="display:none"
    >
        <input
            name="user_id"
            type="hidden"
            value="<?php echo $user['id']?>"
        >

        <input
            id="file_upload"
            name="upload"
            type="file"
            multiple="true"
            value="Select Files"
        >
        <input id="submit_upload" class="button" type="submit">Upload Files</input>
    </form>

    <div id="select_files_button" title="Capture">
        <div class="home_button button theme_color">
            <img src="/img/icons/add_note_icon.svg">
            <h1>Select File</h1>
            <p>Choose the file you wish to upload</p>
        </div>
    </div>
</div>

<script>
    $(function()
    {
        $('#select_files_button').click
        (
            function()
            {
                $('#file_upload').click();
            }
        );

        $('#file_upload_form').change
        (
            function()
            {
                $('#file_upload_form').submit();
            }
        );

        $('#submit_upload_form').click
        (
            function()
            {
                $('#upload_form').submit();
            }
        );
    });
</script>