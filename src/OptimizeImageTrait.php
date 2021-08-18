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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

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

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param Request|null $request
     *
     * @return RedirectResponse
     */
    public function optimizeAction(Request $request = null): RedirectResponse
    {
        if ($request != null) {
            $this->backend->createAndPublish(OptimizeImageConsumer::CONSUMER_TYPE, [
                'mediaId' => $request->get('id'),
            ]);
        }

        $this->session->getFlashBag()->add(
            'sonata_flash_success',
            $this->translator->trans('optimize.image.success', [], 'TinyPngSonataMediaBundle')
        );

        return $this->redirectToList();
    }

    /**
     * @param BackendInterface $backend
     */
    public function setBackend(BackendInterface $backend): void
    {
        $this->backend = $backend;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
