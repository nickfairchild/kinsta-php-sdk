<?php

namespace Nickfairchild\KinstaPhpSdk\Exceptions;

use Exception;
use Saloon\Http\Response;

class KinstaException extends Exception
{
    public function __construct(
        public Response $response,
        string $message,
        int $code
    ) {
        parent::__construct($message, $code);
    }
}
