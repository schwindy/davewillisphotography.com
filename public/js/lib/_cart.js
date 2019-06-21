var acl = 'user';

$(function(){
    if(empty(localStorage.cart)) localStorage.cart = JSON.stringify({});
    if(location.href.indexOf('/dealers/') > -1) acl = 'dealers';
    attach_clicks();
});

function add_to_cart(id, amount)
{
    var cart = get_cart();
    var element = $('#'+id);
    amount = empty(amount)?1:amount;

    if(empty(cart[id]))
    {
        cart[id] =
            {
                'amount':0,
            };
    }

    var options = element.children('.options');
    if(empty(options)) cart[id].amount += amount;
    else
    {
        var skip = false;
        if(empty(cart[id].options)) cart[id].options = {};
        cart[id].amount++;

        options.children().each(function(){
            var type = $(this).attr('name');
            var val = $(this).val();

            if(empty(val))
            {
                swal
                (
                    {
                        title:"Please select a "+type+"!",
                        text:"Please select a "+type+"!",
                        type:"warning",
                        confirmButtonColor:"#5890ff",
                        confirmButtonText:"Okay",
                    }
                );

                skip = true;
            }

            if(empty(cart[id].options[type])) cart[id].options[type] = {};
            if(empty(cart[id].options[type][val])) cart[id].options[type][val] = 0;
            cart[id].options[type][val] = cart[id].options[type][val]+1;
        });

        if(skip) return false;
    }

    set_cart(cart);

    swal
    (
        {
            title:"Item Added to Cart!",
            text:"Would you like to Continue Shopping or Checkout?",
            type:"success",
            confirmButtonColor:"#5890ff",
            confirmButtonText:"Checkout",
            cancelButtonColor:"#5890ff",
            cancelButtonText:"Continue Shopping",
            showCancelButton:true,
        },
        function(){
            location.href = acl==='user'?'/cart':'/'+acl+'/cart';
        }
    );
}

function attach_clicks()
{
    $('.shop_item_button').each(function(){
        $(this).click(function(){
            add_to_cart($(this).parent().prop('id'));
        });
    });

    $('.remove_from_cart').each(function(){
        $(this).click(function(e){
            e.preventDefault();
            remove_from_cart($(this).parent().prop('id'));
            $(this).parent().remove();
            return false;
        });
    });
}

function calculate_total()
{
    var cart = get_cart();
    var subtotal = 0;
    var processing = Number($('#processing').html());

    for(var id in cart)
    {
        var item = cart[id];
        item.price = $('.'+id).children('.price').html();
        item.subtotal = Number(item.price*item.amount);
        subtotal = subtotal+item.subtotal;

        if(item.amount===0)
        {
            continue;
        }
    }

    var total = subtotal+processing;
    $('#subtotal').html(subtotal.toFixed(2));
    $('#total').html(total.toFixed(2));

    if(empty(cart))
    {
        swal
        (
            {
                title:"Your cart is empty!",
                text:"Visit the Shop to add items to your cart.",
                type:"warning",
                confirmButtonColor:"#5890ff",
                confirmButtonText:"Ok"
            },
            function(){
                location.href = acl==='user'?'/shop':'/'+acl+'/shop';
            }
        );
        return false;
    }

    return total;
}

function get_cart()
{
    return JSON.parse(localStorage.cart);
}

function get_cart_html(acl)
{
    var cart = get_cart();
    acl = empty(acl)?'user':acl;

    if(empty(cart))
    {
        swal
        (
            {
                title:"Your cart is empty!",
                text:"Visit the Shop to add items to your cart.",
                type:"warning",
                confirmButtonColor:"#5890ff",
                confirmButtonText:"Ok"
            },
            function(){
                location.href = acl==='user'?'/shop':'/'+acl+'/shop';
            }
        );
        return false;
    }

    ajax
    (
        '/php/portal',
        {
            'run':acl+'/get_cart_html',
            'data':cart,
        },
        function(response){
            if(!response.status)
            {
                swal
                (
                    {
                        title:"Error!",
                        text:"An error occurred! Please refresh the page.",
                        type:"error",
                        confirmButtonColor:"#5890ff",
                        confirmButtonText:"Ok"
                    },
                    function(){
                        location.href = acl==='user'?'/shop':'/'+acl+'/shop';
                    }
                );
                return false;
            }

            $('#cart').html(response.data);
            attach_clicks();
            calculate_total();
        }
    );
}

function remove_from_cart(id)
{
    var cart = get_cart();
    if(empty(cart[id])) return false;

    var display_name = $('#'+id).children('.display_name').html();
    var options = cart[id].options;
    var option_needle = '<br>';
    var option_index = display_name.lastIndexOf(option_needle);

    if(empty(options)) delete cart[id];
    else
    {
        var option = display_name.substr(option_index+option_needle.length);
        var type = option.substr(0, option.indexOf(':')).trim().toLowerCase();
        var val = option.substr(option.indexOf(':')+1).trim();

        cart[id]['amount'] -= cart[id].options[type][val];
        cart[id]['options'][type][val] = 0;

        if(cart[id]['amount']===0) delete cart[id];
    }

    set_cart(cart);
    calculate_total();
}

function reset_cart()
{
    set_cart({});
}

function set_cart(cart)
{
    localStorage.cart = JSON.stringify(cart);
}