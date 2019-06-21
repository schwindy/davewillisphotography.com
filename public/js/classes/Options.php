<script>
    'use strict';

    function Options()
    {
        var button_add = $('#add_option');
        var container = '.options';
        var selector = '.option';

        this.listen = function listen(){
            $(selector).each(function(){
                $(this).children('.remove').each(function(){
                    $(this).off('click');
                    $(this).click(function(){
                        $(this).parent().remove();
                        listen();
                    });
                });
            });

            button_add.off('click');
            button_add.click(function(){
                var html =
                    "<div class='option'>"+
                    "<input class='var' placeholder='Option Type'>"+
                    "<input class='val' placeholder='Option Name'>"+
                    "<button class='remove padding_xxsm_y red_bg white bold'>Remove</button>"+
                    "</div>";

                $(container).prepend(html);
                listen();
            });
        };

        this.listen();
        return this;
    }
</script>