<?php
declare(strict_types = 1);

namespace AppBundle\Exception;

use Exception;

final class NoCookieForYou extends \Exception
{
    public function __construct($message = 'No cookie for you!', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
