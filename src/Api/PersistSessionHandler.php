<?php

declare(strict_types=1);

namespace SocialWeb\Atproto\Api;

interface PersistSessionHandler
{
    /**
     * @param object{accessJwt: string, refreshJwt: string, handle: string, did: string, email?: string} $sessionData
     */
    public function __invoke(SessionEvent $event, object $sessionData): void;
}
