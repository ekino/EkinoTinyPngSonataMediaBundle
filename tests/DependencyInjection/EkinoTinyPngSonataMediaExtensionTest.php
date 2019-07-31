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

namespace Ekino\TinyPngSonataMediaBundle\Tests\DependencyInjection;

use Ekino\TinyPngSonataMediaBundle\DependencyInjection\EkinoTinyPngSonataMediaExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EkinoTinyPngSonataMediaExtensionTest
 *
 * @author Benoit Mazière <benoit.maziere@ekino.com>
 */
class EkinoTinyPngSonataMediaExtensionTest extends TestCase
{
    /**
     * @var EkinoTinyPngSonataMediaExtension
     */
    private $extension;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->extension = new EkinoTinyPngSonataMediaExtension();
    }

    public function testSonataMediaConfig(): void
    {
        $container = $this->createPartialMock(ContainerBuilder::class,
            ['setParameter']
        );

        $container->expects($this->at(0))->method('setParameter')
            ->with('ekino.tiny_png_sonata_media.api_key', 'foo_api_key');
        $container->expects($this->at(1))->method('setParameter')
            ->with('ekino.tiny_png_sonata_media.providers', ['foo_provider', 'bar_provider']);

        $this->extension->load([[
            'tiny_png_api_key' => 'foo_api_key',
            'providers'        => ['foo_provider', 'bar_provider'],
        ]], $container);
    }
}
