<?php
acl_require_admin(CURRENT_PATH);

$row = $db->get_row("SELECT * FROM docs WHERE id='$_REQUEST[id]'");
if(empty($row))redirect_to('/admin/docs/');

$doc = new Doc($row);
$checkbox_public = $doc->is_public==='true'?'check_box':'check_box_outline_blank';

echo __html
(
    'card',
    [
        'class'=>'text_left',
        'text'=>
            __html('h1', ['text'=>"Gallery Document", 'prop' =>['class' =>'text_left']]).
            __html('br').
            __html('edit_button',
            [
                'field'=>"display_name",
                'obj'=>$doc,
                'run'=>"admin/docs/update_display_name",
                'subtext'=>"Enter a new Display Name",
                'title'=>"Display Name",
                'var'=>"Display Name",
                'input_default'=>$doc->display_name,
            ]).
            __html('br').
            __html('edit_button',
            [
                'field'=>"bio",
                'obj'=>$doc,
                'run'=>"admin/docs/update_bio",
                'subtext'=>"Enter a new Description",
                'title'=>"Description",
                'var'=>"Description",
                'input_default'=>$doc->bio,
            ]).
            __html('br').
            __html('edit_button',
            [
                'field'=>"tags",
                'obj'=>$doc,
                'run'=>"admin/docs/update_tags",
                'subtext'=>"Enter Document Tags (CSV)",
                'title'=>"Tags",
                'var'=>"Tags",
                'input_default'=>$doc->tags,
            ]).
            __html('br').
            __html('edit_button',
            [
                'field'=>"category",
                'obj'=>$doc,
                'run'=>"admin/docs/update_category",
                'subtext'=>"Enter Category",
                'title'=>"Category",
                'var'=>"Category",
                'input_default'=>$doc->category,
            ]).
            __html('br').
            __html('edit_button',
            [
                'field'=>"sub_category",
                'obj'=>$doc,
                'run'=>"admin/docs/update_sub_category",
                'subtext'=>"Enter Sub-Category",
                'title'=>"Sub-Category",
                'var'=>"Sub-Category",
                'input_default'=>$doc->sub_category,
            ]).
            "<br><br><div>
                <i id='is_public_button' class='material-icons button hover_opacity float_left'>$checkbox_public</i>
                <p class='float_left'>Is Public?</p>
            </div>".
            __html('br').
            __html('br').
            __html('p', '<b>File URL: </b><a href="'.$doc->file_url.'" target="_blank">'.$doc->file_url.'</a>').
            __html('p', '<b>Title: </b>'.$doc->display_name).
            __html('p', '<b>Description: </b>'.$doc->bio).
            __html('p', '<b>Category: </b>'.$doc->category).
            __html('p', '<b>Sub-Category: </b>'.$doc->sub_category).
            __html('br').
            __html('p', '<b>Type: </b>'.$doc->file_type).
            __html('p', '<b>Extension: </b>'.$doc->file_ext).
            __html('p', '<b>Size: </b>'.get_volume_rounded($doc->file_size)).
            __html('p', '<b>Created: </b>'.$doc->created).
            __html('p', '<b>Updated: </b>'.$doc->updated)
    ]
);

echo __html("gallery_item", ['text'=>$doc->display_name, 'prop'=>(array)$doc]);
?>

<script>
    $(function()
    {
        $('#is_public_button').click(function()
        {
            var icon = $(this).html() === 'check_box'?'check_box_outline_blank':'check_box';
            var is_public = icon === 'check_box'?true:false;
            $(this).html(icon);

            ajax
            (
                PATH_PORTAL,
                {
                    'run':'admin/docs/toggle_public',
                    'id':'<?php echo $doc->id?>',
                    'is_public':is_public,
                },
                function()
                {
                    swal
                    (
                        {
                            title: "Settings Changed",
                            text: "Search Visibility settings were changed successfully!",
                            type: "success",
                            confirmButtonColor: "#5890ff",
                            confirmButtonText: "Ok"
                        }
                    );
                }
            );
        });
    });
</script>
