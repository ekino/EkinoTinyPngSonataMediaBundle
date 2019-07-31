<?php

declare(strict_types=1);

/*
 * This file is part of the ekino/tiny-png-sonata-media-bundle project.
 *
 * (c) Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\TinyPngSonataMediaBundle\Tests\Client;

use Ekino\TinyPngSonataMediaBundle\Client\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 *
 * @author Benoit Mazière <benoit.maziere@ekino.com>
 */
class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->client = new Client('foo');
    }

    public function testOptimizeWithFileExistWithoutOverwrite(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageRegExp('#The file \/(.*)\/tests\/Client\/foo\.png already exists and the overwrite option is false#');

        $this->client->optimize(__DIR__.'/foo.png', __DIR__.'/foo.png', false);
    }
}
