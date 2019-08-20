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

namespace Ekino\TinyPngSonataMediaBundle;

use Ekino\TinyPngSonataMediaBundle\Consumer\OptimizeImageConsumer;
use Sonata\NotificationBundle\Backend\BackendInterface;
use Sonata\NotificationBundle\Backend\QueueDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait OptimizeImageTrait.
 *
 * @author William JEHANNE <william.jehanne@ekino.com>
 */
trait OptimizeImageTrait
{
    /**
     * @var BackendInterface
     */
    private $backend;

    public function optimizeAction(Request $request = null)
    {
        $this->getBackend()->createAndPublish(OptimizeImageConsumer::CONSUMER_TYPE, [
            'mediaId' => $request->get('id'),
        ]);

        $this->get('session')->getFlashBag()->add(
            'sonata_flash_success',
            'Successfully launch the optimization of your media.'
        );

        return $this->redirectToList();
    }

    /**
     * @return BackendInterface
     */
    private function getBackend()
    {
        return $this->container->get('sonata.notification.backend');
    }
}
