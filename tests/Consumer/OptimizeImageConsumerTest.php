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

namespace Ekino\TinyPngSonataMediaBundle\Tests\Consumer;

use Ekino\TinyPngSonataMediaBundle\Client\ClientInterface;
use Ekino\TinyPngSonataMediaBundle\Consumer\OptimizeImageConsumer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sonata\Doctrine\Model\ManagerInterface;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\NotificationBundle\Consumer\ConsumerEvent;
use Sonata\NotificationBundle\Exception\HandlingException;
use Sonata\NotificationBundle\Model\MessageInterface;

/**
 * Class OptimizeImageConsumerTest
 *
 * @author Benoit Mazière <benoit.maziere@ekino.com>
 */
class OptimizeImageConsumerTest extends TestCase
{
    /**
     * @var ManagerInterface|MockObject
     */
    private $mediaManager;

    /**
     * @var Pool|MockObject
     */
    private $pool;

    /**
     * @var ClientInterface|MockObject
     */
    private $client;

    /**
     * @var OptimizeImageConsumer
     */
    private $optimizeImageConsumer;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->mediaManager = $this->createMock(ManagerInterface::class);
        $this->pool         = $this->createMock(Pool::class);
        $this->client       = $this->createMock(ClientInterface::class);

        $this->optimizeImageConsumer = new OptimizeImageConsumer($this->mediaManager, $this->pool, $this->client);
    }

    public function testProcessWithoutMedia(): void
    {
        $this->expectException(HandlingException::class);
        $this->expectExceptionMessage('Media not found - id: 1');

        $this->mediaManager->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->optimizeImageConsumer->process($this->configureEvent());
    }

    private function configureEvent(): ConsumerEvent
    {
        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->any())->method('getValue')->with('mediaId')->willReturn(1);

        return new ConsumerEvent($message);
    }
}
