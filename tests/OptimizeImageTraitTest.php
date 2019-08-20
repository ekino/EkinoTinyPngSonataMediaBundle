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

namespace Ekino\TinyPngSonataMediaBundle\Tests;

use Ekino\TinyPngSonataMediaBundle\OptimizeImageTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sonata\NotificationBundle\Backend\BackendInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class OptimizeImageTraitTest.
 *
 * @author William JEHANNE <william.jehanne@ekino.com>
 */
class OptimizeImageTraitTest extends TestCase
{
    /**
     * @var MockObject|BackendInterface
     */
    private $backend;

    /**
     * @var MockObject|TranslatorInterface
     */
    private $translator;

    /**
     * @var MockObject|Request
     */
    private $request;

    /**
     * @var object
     */
    private $optimizeImageTrait;

    /**
     * Initialize test
     */
    protected function setUp(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $session->set('foobar', 'bar');
        $session->getFlashBag()->set('notice', 'bar');

        $this->backend    = $this->createMock(BackendInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);

        $this->optimizeImageTrait = $this->getObjectForTrait(OptimizeImageTraitDecorate::class);
        $this->optimizeImageTrait->setSession($session);
        $this->optimizeImageTrait->setBackend($this->backend);
        $this->optimizeImageTrait->setTranslator($this->translator);
    }

    /**
     * Test optimize action of OptimizeImageTrait.
     */
    public function testOptimizeAction(): void
    {
        $this->request = $this->createMock(Request::class);

        $this->backend->expects($this->once())->method('createAndPublish');

        $this->optimizeImageTrait->optimizeAction($this->request);
    }
}

trait OptimizeImageTraitDecorate
{
    use OptimizeImageTrait;

    /**
     * @return RedirectResponse
     */
    public function redirectToList(): RedirectResponse
    {
        return new RedirectResponse('/');
    }
}
