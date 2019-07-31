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

namespace Ekino\TinyPngSonataMediaBundle\Listener;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ekino\TinyPngSonataMediaBundle\Consumer\OptimizeImageConsumer;
use Sonata\MediaBundle\Filesystem\Local;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\NotificationBundle\Backend\BackendInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class MediaEventSubscriber
 *
 * @author Benoit Mazière <benoit.maziere@ekino.com>
 */
final class MediaEventSubscriber implements EventSubscriber
{
    private const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg'];

    /**
     * @var array
     */
    private $providers;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var BackendInterface
     */
    private $backend;

    /**
     * @param array $providers
     * @param Pool $pool
     * @param BackendInterface $backend
     */
    public function __construct(array $providers, Pool $pool, BackendInterface $backend)
    {
        $this->providers = $providers;
        $this->pool      = $pool;
        $this->backend   = $backend;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate,
            Events::postPersist,
        ];
    }

    /**
     * @param EventArgs $args
     */
    public function postUpdate(EventArgs $args): void
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        if (!($media = $this->getMedia($args))) {
            return;
        }

        if (!$this->doesBinaryContentChanged($args, $media)) {
            return;
        }

        $this->publishOptimizeMessage($media);
    }

    /**
     * @param EventArgs $args
     */
    public function postPersist(EventArgs $args): void
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        if (!($media = $this->getMedia($args))) {
            return;
        }

        $this->publishOptimizeMessage($media);
    }

    /**
     * @param EventArgs $args
     *
     * @return MediaProviderInterface|null
     */
    private function getProvider(EventArgs $args): ?MediaProviderInterface
    {
        $media = $this->getMedia($args);

        if (!$media instanceof MediaInterface) {
            return null;
        }

        if (!\in_array($media->getProviderName(), $this->providers)) {
            return null;
        }

        if (!\in_array($media->getExtension(), static::ALLOWED_EXTENSIONS)) {
            return null;
        }

        $provider = $this->pool->getProvider($media->getProviderName());

        if (!$provider->getFilesystem()->getAdapter() instanceof Local) {
            return null;
        }

        return $provider;
    }

    /**
     * @param EventArgs $args
     *
     * @return MediaInterface|null
     */
    private function getMedia(EventArgs $args): ?MediaInterface
    {
        if (!$args instanceof LifecycleEventArgs) {
            return null;
        }

        $media = $args->getEntity();

        if (!$media instanceof MediaInterface) {
            return null;
        }

        return $media;
    }

    /**
     * @param MediaInterface $media
     */
    private function publishOptimizeMessage(MediaInterface $media): void
    {
        $this->backend->createAndPublish(OptimizeImageConsumer::CONSUMER_TYPE, [
            'mediaId' => $media->getId(),
        ]);
    }

    /**
     * This method intends to detect if binaryContent changed to prevent relaunched of optimization if binaryContent did not change.
     * This can happen if the binary is not changed (only metadata) or after update size in OptimizeImageConsumer.
     * It can be improved as for now it is only based on the fact that the size of the media changed.
     *
     * @param EventArgs $args
     * @param MediaInterface $media
     *
     * @return bool
     */
    private function doesBinaryContentChanged(EventArgs $args, MediaInterface $media): bool
    {
        if (!$args instanceof LifecycleEventArgs) {
            return false;
        }

        /** @var EntityManager $em */
        $em = $args->getEntityManager();

        $provider = $this->pool->getProvider($media->getProviderName());
        /** @var Local $adapter */
        $adapter   = $provider->getFilesystem()->getAdapter();
        $directory = $adapter->getDirectory();

        $path = sprintf('%s/%s',
            $directory, $provider->generatePrivateUrl($media, MediaProviderInterface::FORMAT_REFERENCE)
        );
        $file = new File($path);
        $size = $file->getSize();

        return \array_key_exists('size', $em->getUnitOfWork()->getEntityChangeSet($media))
            && $size !== $em->getUnitOfWork()->getEntityChangeSet($media)['size'][1];
    }
}
