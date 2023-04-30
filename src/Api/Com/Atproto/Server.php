<?php

declare(strict_types=1);

namespace SocialWeb\Atproto\Api\Com\Atproto;

use Com\Atproto\Server as ServerLexicon;
use Psr\Http\Message\UriInterface;
use SocialWeb\Atproto\Api\Options;
use SocialWeb\Atproto\Api\XrpcResponseError;

use function json_decode;
use function json_encode;

/**
 * Maybe this class should be auto-generated from the sources in
 * `resources/bluesky-social/atproto/lexicons`?
 */
class Server implements ServerLexicon
{
    public function __construct(
        private readonly UriInterface $service,
        private readonly Options $options,
    ) {
    }

    public function createSession(string $identifier, string $password): object
    {
        $url = $this->service->withPath('/xrpc/com.atproto.server.createSession');

        $request = $this->options->requestFactory->createRequest('POST', $url);
        $request = $request->withBody($this->options->streamFactory->createStream((string) json_encode([
            'identifier' => $identifier,
            'password' => $password,
        ])));

        // Much of the following should be abstracted out of here.
        $request = $request->withHeader('content-type', 'application/json');
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

        /** @var object{accessJwt: string, refreshJwt: string, handle: string, did: string, email?: string} */
        return $body;
    }

    public function getSession(): object
    {
        $url = $this->service->withPath('/xrpc/com.atproto.server.getSession');

        // Much of the following should be abstracted out of here.
        if ($this->options->session === null) {
            throw new XrpcResponseError('Invalid session');
        }

        $request = $this->options->requestFactory->createRequest('GET', $url);

        $request = $request->withHeader('authorization', 'Bearer ' . $this->options->session->accessJwt);
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

        /** @var object{accessJwt: string, refreshJwt: string, handle: string, did: string, email?: string} */
        return $body;
    }
}
