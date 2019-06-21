<?php
/** Output a SweetAlert Popup Window (Javascript)
 * @param array $args (optional)
 *  $args['confirm_button_color']:string - swal() confirmButtonColor parameter (Default: '#5890ff')
 *  $args['confirm_text']:string - swal() confirmButtonText parameter (Default: 'Ok')
 *  $args['text']:string - swal() text parameter (Default: 'An error occurred. Please try again.')
 *  $args['title']:string - swal() title parameter (Default: 'Error!')
 *  $args['type']:string - swal() type parameter (Default: 'error')
 * @param string $redirect (optional) - Redirect to this URL when User clicks the Alert Confirm Buttton
 * @return string $html - SweetAlert <script> Element
 */
function __alert($args = [], $redirect = null)
{
    if (is_string($args)) {
        $args = ['text' => $args];
    }

    $args['confirm_button_color'] = empty($args['confirm_button_color']) ? '#5890ff' : $args['confirm_button_color'];
    $args['confirm_text'] = empty($args['confirm_text']) ? 'Ok' : $args['confirm_text'];
    $args['redirect'] = empty($redirect) ? null : $redirect;
    $args['text'] = empty($args['text']) ? 'An error occurred. Please try again.' : $args['text'];
    $args['title'] = empty($args['title']) ? 'Error!' : $args['title'];
    $args['type'] = empty($args['type']) ? 'error' : $args['type'];

    return "<script>
        $(function()
        {
            swal
            (
                {
                    confirmButtonColor: '$args[confirm_button_color]',
                    confirmButtonText: '$args[confirm_text]',
                    text: '$args[text]',
                    title: '$args[title]',
                    type: '$args[type]',
                },
                function()
                {
                    if(empty('$args[redirect]'))return false;
                    if('$args[redirect]' == 'back')return kek.SESSION.go_back();
                    return location.href = '$args[redirect]';
                }
            );
        });
    </script>";
}