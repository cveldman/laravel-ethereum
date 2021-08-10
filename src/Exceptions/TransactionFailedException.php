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

class TransactionFailedException extends Exception
{

    /**
     * @var Error
     */
    public $error;

    public function __construct($error, $code = 0, Throwable $previous = null)
    {
        $this->error = $error;
        parent::__construct($error->getMessage(), $code, $previous);
    }
}