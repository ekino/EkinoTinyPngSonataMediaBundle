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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

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
     * @var ContainerBuilder|MockObject
     */
    private $containerBuilder;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->extension        = new EkinoTinyPngSonataMediaExtension();
        $this->containerBuilder = $this->createMock(ContainerBuilder::class);
    }

    public function testSonataMediaConfig(): void
    {
        $clientDefinition = $this->createMockDefinition();
        $clientDefinition
            ->expects($this->exactly(2))
            ->method('replaceArgument')
            ->withConsecutive(
                [0, 'foo_api_key'],
                [0, ['foo_provider', 'bar_provider']]
            )
            ->willReturnSelf();

        $this->containerBuilder
            ->expects($this->exactly(2))
            ->method('findDefinition')
            ->withConsecutive(
                ['ekino.tiny_png_sonata_media.tinfy.client'],
                ['ekino_tiny_png_sonata_media.doctrine.event_subscriber']
            )
            ->willReturn($clientDefinition);

        $this->extension->load([[
            'tiny_png_api_key' => 'foo_api_key',
            'providers'        => ['foo_provider', 'bar_provider'],
        ]], $this->containerBuilder);
    }

    /**
     * @return MockObject
     */
    private function createMockDefinition(): MockObject
    {
        return $this->createMock(Definition::class);
    }
}
