<?php
/**
 * Created by PhpStorm.
 * User: martijndebruijn
 * Date: 8/9/21
 * Time: 2:50 PM
 */

namespace Appbakkers\Ethereum\Exceptions;

use Exception;
use Throwable;

class InsufficientAllowanceException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}