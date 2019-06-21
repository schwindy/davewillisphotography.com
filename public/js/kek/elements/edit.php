<script>
    var element;
    $(function(){
        __construct();

        function __construct()
        {
            element = __element();
            __ide();
            __listen();
            if(!empty(element.properties.class)) $('#preview').attr('class', element.properties.class);
        }

        function __element()
        {
            return new KekElement
            (
                {
                    "id":"<?php echo $obj->id?>",
                    "type":"<?php echo empty($obj->type) ? "" : $obj->type?>",
                    "display_name":"<?php echo empty($obj->display_name) ? "" : $obj->display_name?>",
                    "data":<?php echo json_encode(nl2br($obj->data_clean))?>,
                    "options":JSON.parse('<?php echo json_encode($obj->options)?>'),
                    "properties":JSON.parse('<?php echo json_encode($obj->properties)?>'),
                    "created":"<?php echo empty($obj->created) ? "" : $obj->created?>",
                    "updated":"<?php echo empty($obj->updated) ? "" : $obj->updated?>",
                }
            )
        }

        function __listen()
        {
            $('#preview').html(__kek_decode(nl2br(element.data), element, true));
            $('#data').on('change', function(){
                if(!empty(element.properties.class)) $('#preview').attr('class', element.properties.class);
            });

            $(window).bind('keydown', function(event){
                if(event.ctrlKey || event.metaKey)
                {
                    switch(String.fromCharCode(event.which).toLowerCase())
                    {
                        case 's':
                            event.preventDefault();
                            $('#preview').empty();
                            $('#preview').html(__kek_decode(nl2br($('#data').val()), element, true));
                            $('#kek_submit').click();
                            break;
                    }
                }
            });
        }

        function __ide()
        {
            BehaveHooks.add(['keyup'], function(data){
                __ide_lines(data);
            });
            BehaveHooks.add(['init:after'], function(data){
                __ide_lines(data);
            });

            new Behave
            ({
                autoIndent:true,
                autoOpen:true,
                autoStrip:true,
                overwrite:true,
                replaceTab:true,
                softTabs:true,
                tabSize:4,
                textarea:document.getElementById('data'),
            });
        }

        function __ide_lines(data)
        {
            var line_numbers = document.getElementsByClassName('line_numbers')[0];
            var html = '';
            var i;
            var numLines = data.lines.total;
            var padding = parseInt(getComputedStyle(data.editor.element)['padding']);

            for(i = 0; i < numLines; i++)
            {html += '<div>'+(i+1)+'</div>';}
            line_numbers.innerHTML = html;
        }
    });
</script>