<?php
acl_require_admin(CURRENT_PATH);
?>
<br>
<div class="card">
    <?php echo __html('h2', ['text' => 'Create Shop Item']) ?>
    <form id="create_element" class="kek_form width_100 margin_auto_x">
        <input type="hidden" id="type" value="shop_item">

        <p class="bold text_left">Item Name</p>
        <input id="display_name" placeholder="Item Name">

        <p class="bold text_left">Item Description</p>
        <textarea id="item_bio" placeholder="Item Description"></textarea>

        <p class="bold text_left">Price ($)</p>
        <input id="item_price" placeholder="0.00">

        <p class="bold text_left">Image URL (ex: /img/logo.png)</p>
        <input id="item_image" placeholder="Image URL">

        <p class="bold text_left">Notes</p>
        <textarea id="notes" placeholder="Notes (Admin Only)"></textarea>
    </form>
    <br>
    <h3 class="bold text_left">Options</h3>
    <?php echo generate_options_element(null) ?>

    <h3 class="bold text_left">Properties</h3>
    <?php echo generate_properties_element(null, 'nojs') ?>
</div>

<script>
    var properties = [];
    $(function(){
        properties = read_properties();

        $('#submit').click(function(){
            properties = read_properties();
            properties.bio = $('#create_element').children('#item_bio').val();
            properties.image = $('#create_element').children('#item_image').val();
            properties.price = Number($('#create_element').children('#item_price').val().replace("$", "0")).toFixed(2);

            ajax
            (
                '/php/portal',
                {
                    'run':'admin/kek/save_element',
                    'display_name':$('#create_element').children('#display_name').val(),
                    'type':$('#create_element').children('#type').val(),
                    'notes':$('#create_element').children('#notes').val(),
                    'options':__read('.option'),
                    'properties':properties,
                },
                function(response){
                    if(!response.status)
                    {
                        swal
                        (
                            {
                                title:"Error!",
                                text:response.message,
                                type:"error",
                                confirmButtonColor:"#5890ff",
                                confirmButtonText:"Ok"
                            }
                        );

                        return false;
                    }

                    swal
                    (
                        {
                            title:"Success!",
                            text:response.message,
                            type:"success",
                            confirmButtonColor:"#5890ff",
                            confirmButtonText:"Ok"
                        },
                        function(){
                            location.href = '/admin/kek/'+response.data.path+'/view?id='+response.data.id
                        }
                    );
                }
            );
        });

        $('#add_property').click(function(){
            var html =
                "<div class='property'>"+
                "<input class='var' placeholder='Property Name'>"+
                "<input class='val' placeholder='Property Value'>"+
                "<button class='remove padding_xxsm_y red_bg white bold'>Remove</button>"+
                "</div>";

            $('.properties').prepend(html);
            attach_clicks();
        });

        attach_clicks();
    });

    function attach_clicks()
    {
        $('.property').each(function(){
            $(this).children('.remove').each(function(){
                $(this).off('click');
                $(this).click(function(){
                    $(this).parent().remove();
                    properties = read_properties();
                    attach_clicks();
                });
            });
        });
    }

    function read_properties()
    {
        var properties = {};

        $('.property').each(function(){
            if(!empty($(this).children('.var').val()))
            {
                properties[$(this).children('.var').val()] = $(this).children('.val').val();
            }
        });

        return properties;
    }
</script>