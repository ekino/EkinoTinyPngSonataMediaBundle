TinyPngSonataMediaBundle
========================

[![Latest Stable Version](https://poser.pugx.org/ekino/tiny-png-sonata-media-bundle/v/stable)](https://packagist.org/packages/ekino/tiny-png-sonata-media)
[![Build Status](https://travis-ci.org/ekino/EkinoTinyPngSonataMediaBundle.svg?branch=master)](https://travis-ci.org/ekino/EkinoTinyPngSonataMediaBundle)
[![Coverage Status](https://coveralls.io/repos/ekino/EkinoTinyPngSonataMediaBundle/badge.svg?branch=master&service=github)](https://coveralls.io/github/ekino/EkinoTinyPngSonataMediaBundle?branch=master)
[![Total Downloads](https://poser.pugx.org/ekino/tiny-png-sonata-media-bundle/downloads)](https://packagist.org/packages/ekino/tiny-png-sonata-media-bundle)

This is a *work in progress*, so if you'd like something implemented please
feel free to ask for it or contribute to help us!

# Resources
- [Documentation](./docs/00-docs.md)

# Purpose

Automatize image optimization through tinyPNG service. You can only use the client or get the full process with 
sonata media and sonata notification.

# Installation

## Step 1: add dependency

```bash
$ composer require ekino/tiny-png-sonata-media-bundle
```

## Step 2: register the bundle

### Symfony 2 or 3:

```php
<?php

// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Ekino\TinyPngSonataMediaBundle\EkinoTinyPngSonataMediaBundle(),
        // ...
    ];
}
```

### Symfony 4:

```php
<?php

// config/bundles.php

return [
    // ...
    Ekino\TinyPngSonataMediaBundle\EkinoTinyPngSonataMediaBundle::class => ['all' => true],
    // ...
];
```

## Step 3: configure the bundle

```yaml
ekino_tiny_png_sonata_media:
    tiny_png_api_key:               ~   # required
    providers:                      []  # default
    max_compression_count_by_month: 500 # default value defined in config
```

## Step 4: define the sonata notification queue for asynchronous behaviour

# Usage

## Use the tinyPng client

Client can be used directly to optimize images through tinyPNG API. However, image optimization should not be done 
synchronously as it takes time.

If you know what you are doing, you can use the `ekino.tiny_png_sonata_media.tinify.client` and its `optimize` method:

```php
<?php

$client->optimize($inputPath, $outputPath, $overwrite);
```

## Full process with sonata media & notification

This bundle listen doctrine events (postPersist & postUpdate) on media entity. As soon as the media's provider is in 
the whitelist (defined in configuration), it will publish a sonata notification message 
(type: `ekino.tiny_png_sonata_media.optimize_image`) to be handled by a consumer 
(`Ekino\TinyPngSonataMediaBundle\Consumer\OptimizeImageConsumer`). This consumer will contact tinyPNG API for 
optimization, replace it on the server and update media size in database.

## Suggest install LiipMonitorBundle

This bundle provides a service using [liip/monitor-bundle][1] to check the count of compressions made this month. 

# Note

- Only Sonata\MediaBundle\Filesystem\Local adapter is supported for now.
- Only png, jpg & jpeg files extensions are handled by this bundle as the tinyPNG only handle those ones.
- Regeneration of thumbnails after optimization is not yet supported.

[1]: https://github.com/liip/LiipMonitorBundle
