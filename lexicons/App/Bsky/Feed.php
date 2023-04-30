<?php

declare(strict_types=1);

namespace App\Bsky;

/**
 * This interface should be auto-generated from the sources in
 * `resources/bluesky-social/atproto/lexicons`.
 *
 * @phpstan-type PostView = object{uri: string, cid: string, author: object, record: mixed, replyCount: int, repostCount: int, likeCount: int}
 * @phpstan-type ReplyRef = object{root: PostView, parent: PostView}
 * @phpstan-type FeedViewPost = object{post: PostView, reply?: ReplyRef}
 */
interface Feed
{
    /**
     * A view of the user's home timeline.
     *
     * @return object{cursor?: string, feed: FeedViewPost[]}
     *
     * @phpstan-param int<1, 100> $limit
     */
    public function getTimeline(int $limit = 50, ?string $cursor = null, ?string $algorithm = null): object;
}
