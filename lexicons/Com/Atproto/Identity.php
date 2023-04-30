<?php

declare(strict_types=1);

namespace Com\Atproto;

/**
 * This interface should be auto-generated from the sources in
 * `resources/bluesky-social/atproto/lexicons`.
 */
interface Identity
{
    /**
     * Provides the DID of a repo.
     *
     * @param string | null $handle The handle to resolve. If not supplied, will resolve the host's own handle.
     *
     * @return object{did: string}
     */
    public function resolveHandle(?string $handle): object;
}
