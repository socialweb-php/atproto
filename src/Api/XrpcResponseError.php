<?php

declare(strict_types=1);

namespace SocialWeb\Atproto\Api;

use RuntimeException;
use SocialWeb\Atproto\AtprotoException;
use Throwable;

class XrpcResponseError extends RuntimeException implements AtprotoException
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?string $error = null,
        ?Throwable $previous = null,
    ) {
        if ($error !== null && $message === '') {
            $message = $error;
        } elseif ($error !== null && $error !== '') {
            $message = "[$error] $message";
        }

        parent::__construct($message, $code, $previous);
    }
}
