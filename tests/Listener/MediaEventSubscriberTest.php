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

namespace Ekino\TinyPngSonataMediaBundle\Tests\Listener;

use Doctrine\ORM\Events;
use Ekino\TinyPngSonataMediaBundle\Listener\MediaEventSubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\NotificationBundle\Backend\BackendInterface;

/**
 * Class MediaEventSubscriberTest
 *
 * @author Benoit Mazière <benoit.maziere@ekino.com>
 */
class MediaEventSubscriberTest extends TestCase
{
    /**
     * @var Pool|MockObject
     */
    private $pool;

    /**
     * @var BackendInterface|MockObject
     */
    private $backend;

    /**
     * @var MediaEventSubscriber
     */
    private $mediaEventSubscriber;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->pool    = $this->createMock(Pool::class);
        $this->backend = $this->createMock(BackendInterface::class);

        $this->mediaEventSubscriber = new MediaEventSubscriber([], $this->pool, $this->backend);
    }

    public function testGetSubscribedEvents(): void
    {
        $this->assertSame([
            Events::postUpdate,
            Events::postPersist,
        ], $this->mediaEventSubscriber->getSubscribedEvents());
    }
}
