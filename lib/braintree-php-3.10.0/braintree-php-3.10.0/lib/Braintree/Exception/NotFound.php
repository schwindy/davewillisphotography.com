<?php

namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when a record could not be found.
 *
 * @package    Braintree
 * @subpackage Exception
 * @copyright  2015 Braintree, a division of PayPal, Inc.
 */
class NotFound extends Exception
{

}

class_alias('Braintree\Exception\NotFound', 'Braintree_Exception_NotFound');
