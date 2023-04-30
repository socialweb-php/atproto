<?php

declare(strict_types=1);

namespace Com\Atproto;

/**
 * This interface should be auto-generated from the sources in
 * `resources/bluesky-social/atproto/lexicons`.
 */
interface Server
{
    /**
     * Create an authentication session.
     *
     * @param string $identifier Handle or other identifier supported by the server for the authenticating user.
     *
     * @return object{accessJwt: string, refreshJwt: string, handle: string, did: string, email?: string}
     */
    public function createSession(string $identifier, string $password): object;

    /**
     * Get information about the current session.
     *
     * @return object{handle: string, did: string, email?: string}
     */
    public function getSession(): object;
}
