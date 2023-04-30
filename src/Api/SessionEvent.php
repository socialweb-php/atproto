<?php

declare(strict_types=1);

namespace SocialWeb\Atproto\Api;

enum SessionEvent: string
{
    case Create = 'create';
    case CreateFailed = 'create-failed';
    case Deleted = 'deleted';
    case Expired = 'expired';
    case Update = 'update';
}
