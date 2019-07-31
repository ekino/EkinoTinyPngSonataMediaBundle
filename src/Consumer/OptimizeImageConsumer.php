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

namespace Ekino\TinyPngSonataMediaBundle\Consumer;

use Ekino\TinyPngSonataMediaBundle\Client\ClientInterface;
use Sonata\Doctrine\Model\ManagerInterface;
use Sonata\MediaBundle\Filesystem\Local;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\NotificationBundle\Consumer\ConsumerEvent;
use Sonata\NotificationBundle\Consumer\ConsumerInterface;
use Sonata\NotificationBundle\Exception\HandlingException;
use Symfony\Component\HttpFoundation\File\File;
use Tinify\AccountException;
use Tinify\ClientException;
use Tinify\ConnectionException;
use Tinify\ServerException;

/**
 * Class OptimizeImageConsumer
 *
 * @author Benoit Mazière <benoit.mazière@ekino.com>
 */
final class OptimizeImageConsumer implements ConsumerInterface
{
    public const CONSUMER_TYPE            = 'ekino.tiny_png_sonata_media.optimize_image';
    private const ERRONEOUS_RESTART_COUNT = 99;

    /**
     * @var ManagerInterface
     */
    private $mediaManager;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ManagerInterface $mediaManager
     * @param Pool             $pool
     * @param ClientInterface  $client
     */
    public function __construct(ManagerInterface $mediaManager, Pool $pool, ClientInterface $client)
    {
        $this->mediaManager = $mediaManager;
        $this->pool         = $pool;
        $this->client       = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ConsumerEvent $event): void
    {
        $message = $event->getMessage();
        /** @var MediaInterface $media */
        $media = $this->mediaManager->find($message->getValue('mediaId'));

        if (empty($media)) {
            $message->setRestartCount(static::ERRONEOUS_RESTART_COUNT);
            throw new HandlingException(sprintf('Media not found - id: %s', $message->getValue('mediaId')));
        }

        try {
            $provider = $this->pool->getProvider($media->getProviderName());
            /** @var Local $adapter */
            $adapter   = $provider->getFilesystem()->getAdapter();
            $directory = $adapter->getDirectory();

            $path = sprintf('%s/%s',
                $directory, $provider->generatePrivateUrl($media, MediaProviderInterface::FORMAT_REFERENCE)
            );
            $this->client->optimize($path, $path);

            // fix media size in database after optimization
            $binaryContent = new File($path);
            $media->setSize($binaryContent->getSize());
            $this->mediaManager->save($media);
        } catch(AccountException $e) {
            throw new HandlingException(sprintf('Verify your API key and account limit - id: %s - message: %s',
                $message->getValue('mediaId'), $e->getMessage()));
        } catch(ClientException $e) {
            throw new HandlingException(sprintf('Check your source image and request options - id: %s - message: %s',
                $message->getValue('mediaId'), $e->getMessage()));
        } catch(ServerException $e) {
            throw new HandlingException(sprintf('Temporary issue with the Tinify API - id: %s - message: %s',
                $message->getValue('mediaId'), $e->getMessage()));
        } catch(ConnectionException $e) {
            throw new HandlingException(sprintf('A network connection error occurred - id: %s - message: %s',
                $message->getValue('mediaId'), $e->getMessage()));
        } catch(\Throwable $e) {
            $message->setRestartCount(static::ERRONEOUS_RESTART_COUNT);
            throw new HandlingException(sprintf('Something else went wrong, unrelated to the Tinify API - id: %s - message: %s',
                $message->getValue('mediaId'), $e->getMessage()));
        }
    }
}
