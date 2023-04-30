<?php

/**
 * This file is part of socialweb/atproto
 *
 * socialweb/atproto is free software: you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either version
 * 3 of the License, or (at your option) any later version.
 *
 * socialweb/atproto is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with socialweb/atproto.
 * If not, see <https://www.gnu.org/licenses/>.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license https://opensource.org/license/lgpl-3-0/ GNU Lesser General Public License version 3 or later
 */

declare(strict_types=1);

namespace Com\Atproto;

use SocialWeb\Atproto\Lexicon\Lexicon;
use SocialWeb\Atproto\Lexicon\Type;

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
    #[Lexicon(
        lexicon: 1,
        id: 'com.atproto.identity.resolveHandle',
        type: Type::Query,
        description: 'Provides the DID of a repo.',
    )]
    public function resolveHandle(?string $handle): object;
}
