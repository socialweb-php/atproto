<h1 align="center">socialweb/atproto</h1>

<p align="center">
    <strong>A PHP library for integrating with and communicating over the AT Protocol</strong>
</p>

<p align="center">
    <a href="https://github.com/socialweb-php/atproto"><img src="https://img.shields.io/badge/source-socialweb/atproto-blue.svg?style=flat-square" alt="Source Code"></a>
    <a href="https://packagist.org/packages/socialweb/atproto"><img src="https://img.shields.io/packagist/v/socialweb/atproto.svg?style=flat-square&label=release" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/socialweb/atproto.svg?style=flat-square&colorB=%238892BF" alt="PHP Programming Language"></a>
    <a href="https://github.com/socialweb-php/atproto/blob/main/NOTICE"><img src="https://img.shields.io/packagist/l/socialweb/atproto.svg?style=flat-square&colorB=darkcyan" alt="Read License"></a>
    <a href="https://github.com/socialweb-php/atproto/actions/workflows/continuous-integration.yml"><img src="https://img.shields.io/github/actions/workflow/status/socialweb-php/atproto/continuous-integration.yml?branch=main&style=flat-square&logo=github" alt="Build Status"></a>
    <a href="https://codecov.io/gh/socialweb-php/atproto"><img src="https://img.shields.io/codecov/c/gh/socialweb-php/atproto?label=codecov&logo=codecov&style=flat-square" alt="Codecov Code Coverage"></a>
    <a href="https://shepherd.dev/github/socialweb-php/atproto"><img src="https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fshepherd.dev%2Fgithub%2Fsocialweb-php%2Fatproto%2Fcoverage" alt="Psalm Type Coverage"></a>
</p>

## About

> 🚨 **DANGER, WILL ROBINSON!** \
> This library is extremely, very rough. I'd love to get your input and help,
> though, which is why I'm putting it out very, very, very early. If you're
> Interested in helping develop this, please feel free to open an issue, open a
> pull request, or contact me with any questions.

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md).
By participating in this project and its community, you are expected to
uphold this code.

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require socialweb/atproto
```

## Usage

```php
// This is very rough and in no way represents the final recommended
// usage of this library.
use SocialWeb\Atproto\Api\Client;

$client = new Client('https://bsky.social');
$client->login('YOUR_EMAIL_ADDRESS', 'YOUR_PASSWORD');

$homeFeed = $client->getTimeline()->feed;

foreach ($homeFeed as $item) {
    echo "{$item->post->author->displayName} (@{$item->post->author->handle}) says:\n\n";
    echo "{$item->post->record->text}\n\n";

    if (isset($item->post->record->reply)) {
        echo "in reply to {$item->post->record->reply->parent->uri}\n\n";
    }

    echo str_repeat('-', 72);
    echo "\n\n";
}
```

## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](CONTRIBUTING.md).

## Coordinated Disclosure

Keeping user information safe and secure is a top priority, and we welcome the
contribution of external security researchers. If you believe you've found a
security issue in software that is maintained in this repository, please read
[SECURITY.md](SECURITY.md) for instructions on submitting a vulnerability report.

## Copyright and License

socialweb/atproto is copyright © [Ben Ramsey](https://benramsey.com)
and licensed for use under the terms of the
GNU Lesser General Public License (LGPL-3.0-or-later) as published by the Free
Software Foundation. Please see [COPYING.LESSER](COPYING.LESSER),
[COPYING](COPYING), and [NOTICE](NOTICE) for more information.
