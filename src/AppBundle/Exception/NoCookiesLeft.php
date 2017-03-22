<?php
declare(strict_types = 1);

namespace AppBundle\Exception;

use Exception;

final class NoCookiesLeft extends \Exception
{
    public function __construct($message = 'There are no more cookies :(', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
