<?php

declare(strict_types=1);

namespace SocialWeb\Atproto\Api\App\Bsky;

use App\Bsky\Feed as FeedLexicon;
use Psr\Http\Message\UriInterface;
use SocialWeb\Atproto\Api\Options;
use SocialWeb\Atproto\Api\XrpcResponseError;
use SocialWeb\Atproto\Lexicon\Lexicon;
use SocialWeb\Atproto\Lexicon\Type;

use function http_build_query;
use function json_decode;

/**
 * @phpstan-import-type FeedViewPost from FeedLexicon
 */
class Feed implements FeedLexicon
{
    public function __construct(
        private readonly UriInterface $service,
        private readonly Options $options,
    ) {
    }

    #[Lexicon(
        lexicon: 1,
        id: 'app.bsky.feed.getTimeline',
        type: Type::Query,
        description: 'A view of the user\'s home timeline.',
    )]
    public function getTimeline(int $limit = 50, ?string $cursor = null, ?string $algorithm = null): object
    {
        $url = $this->service->withPath('/xrpc/app.bsky.feed.getTimeline');
        $params = ['limit' => $limit];

        if ($cursor !== null) {
            $params['cursor'] = $cursor;
        }

        if ($algorithm !== null) {
            $params['algorithm'] = $algorithm;
        }

        $url = $url->withQuery(http_build_query($params));

        // Much of the following should be abstracted out of here.
        $request = $this->options->requestFactory->createRequest('GET', $url);

        if ($this->options->session !== null) {
            $request = $request->withHeader('authorization', 'Bearer ' . $this->options->session->accessJwt);
        }

        $response = $this->options->httpClient->sendRequest($request);

        $body = (object) [];
        $rawBody = $response->getBody()->getContents();
        if ($rawBody !== '') {
            $body = (object) json_decode($rawBody);
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new XrpcResponseError(
                (string) ($body->message ?? $body->error ?? $response->getReasonPhrase()),
                $response->getStatusCode(),
                (string) ($body->error ?? ''),
            );
        }

        /** @var object{cursor?: string, feed: FeedViewPost[]} */
        return $body;
    }
}
