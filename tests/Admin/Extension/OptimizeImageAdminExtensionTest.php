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

namespace Ekino\TinyPngSonataMediaBundle\Tests\Admin\Extension;

use Ekino\TinyPngSonataMediaBundle\Admin\Extension\OptimizeImageAdminExtension;
use PHPUnit\Framework\TestCase;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class OptimizeImageAdminExtensionTest
 *
 * @author William JEHANNE <william.jehanne@ekino.com>
 */
class OptimizeImageAdminExtensionTest extends TestCase
{
    /**
     * @var OptimizeImageAdminExtension
     */
    private $optimizeImageAdminExtension;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->optimizeImageAdminExtension = new OptimizeImageAdminExtension();
    }

    /**
     * Test configureActionButtons of OptimizeImageAdminExtension.
     */
    public function testConfigureActionButtons(): void
    {
        $admin = $this->createMock(AdminInterface::class);

        $list = $this->optimizeImageAdminExtension->configureActionButtons($admin, [], '', '');

        $this->assertArrayHasKey('custom_action', $list);
        $this->assertArrayHasKey('template', $list['custom_action']);
        $this->assertSame('@EkinoTinyPngSonataMedia/OptimizeImageAdmin/button.html.twig', $list['custom_action']['template']);
    }

    /**
     * Test configureRoutes of OptimizeImageAdminExtension.
     */
    public function testConfigureRoutes(): void
    {
        $admin      = $this->createMock(AdminInterface::class);
        $collection = $this->createMock(RouteCollection::class);

        $collection->expects($this->once())->method('add')->with('optimize',
            'optimize-image/{id}',
            [],
            ['id' => '\d+']
        );

        $this->optimizeImageAdminExtension->configureRoutes($admin, $collection);
    }
}
