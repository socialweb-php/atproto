<?php

declare(strict_types=1);

namespace SocialWeb\Atproto\Api;

use App\Bsky\Feed as FeedLexicon;
use Psr\Http\Message\UriInterface;
use SocialWeb\Atproto\Api\App\Bsky\Feed;
use SocialWeb\Atproto\Api\Com\Atproto\Identity;
use SocialWeb\Atproto\Api\Com\Atproto\Server;

/**
 * @phpstan-import-type FeedViewPost from FeedLexicon
 */
class Client
{
    private readonly UriInterface $service;
    private ?Server $atprotoServer = null;
    private ?Identity $atprotoIdentity = null;
    private ?Feed $bskyFeed = null;

    public function __construct(
        UriInterface | string $service,
        private readonly ?PersistSessionHandler $persistSession = null,
        private readonly Options $options = new Options(),
    ) {
        $this->service = $service instanceof UriInterface
            ? $service
            : $this->options->uriFactory->createUri($service);
    }

    public function login(string $identifier, string $password): object
    {
        $this->options->session = $this->getAtprotoServer()->createSession($identifier, $password);

        if ($this->persistSession instanceof PersistSessionHandler) {
            ($this->persistSession)(SessionEvent::Create, $this->options->session);
        }

        return $this->options->session;
    }

    /**
     * @param object{accessJwt: string, refreshJwt: string, handle: string, did: string, email?: string} $session
     */
    public function resumeSession(object $session): object
    {
        $this->options->session = $session;
        $session = $this->getAtprotoServer()->getSession();

        // Catch expired session and call ($this->persistSession)(AtpSessionEvent::Expired, $session).
        // If we have a refreshJwt, maybe automatically refresh the session?
        return $session;
    }

    public function resolveHandle(?string $handle): string
    {
        $result = $this->getAtprotoIdentity()->resolveHandle($handle);

        return $result->did;
    }

    /**
     * @return object{cursor?: string, feed: FeedViewPost[]}
     *
     * @phpstan-param int<1, 100> $limit
     */
    public function getTimeline(int $limit = 50, ?string $cursor = null, ?string $algorithm = null): object
    {
        return $this->getBskyFeed()->getTimeline($limit, $cursor, $algorithm);
    }

    private function getAtprotoServer(): Server
    {
        if ($this->atprotoServer === null) {
            $this->atprotoServer = new Server($this->service, $this->options);
        }

        return $this->atprotoServer;
    }

    private function getAtprotoIdentity(): Identity
    {
        if ($this->atprotoIdentity === null) {
            $this->atprotoIdentity = new Identity($this->service, $this->options);
        }

        return $this->atprotoIdentity;
    }

    private function getBskyFeed(): Feed
    {
        if ($this->bskyFeed === null) {
            $this->bskyFeed = new Feed($this->service, $this->options);
        }

        return $this->bskyFeed;
    }
}
