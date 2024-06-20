<?php

declare(strict_types=1);

namespace Mijnkantoor\NMBRS\Exceptions;

class NmbrsException extends \Exception
{

    /**
     * isUnauthorizedError
     *
     * @return bool
     */
    public function isUnauthorizedError(): bool
    {
        preg_match('/\d{4}:/', $this->getMessage(), $matches);
        if ('1002:' === ($matches[0] ?? '')) {
            return true;
        }
        return false;
    }
}
