<?php

declare(strict_types=1);

namespace SocialWeb\Atproto\Api\Com\Atproto;

use Com\Atproto\Identity as IdentityLexicon;
use Psr\Http\Message\UriInterface;
use SocialWeb\Atproto\Api\Options;
use SocialWeb\Atproto\Api\XrpcResponseError;

use function http_build_query;
use function json_decode;

/**
 * Maybe this class should be auto-generated from the sources in
 * `resources/bluesky-social/atproto/lexicons`?
 */
class Identity implements IdentityLexicon
{
    public function __construct(
        private readonly UriInterface $service,
        private readonly Options $options,
    ) {
    }

    public function resolveHandle(?string $handle): object
    {
        $url = $this->service->withPath('/xrpc/com.atproto.identity.resolveHandle');

        if ($handle !== null) {
            $url = $url->withQuery(http_build_query(['handle' => $handle]));
        }

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

        /** @var object{did: string} */
        return $body;
    }
}
