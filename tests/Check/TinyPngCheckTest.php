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

namespace Ekino\TinyPngSonataMediaBundle\Tests\Check;

use Ekino\TinyPngSonataMediaBundle\Check\TinyPngCheck;
use Ekino\TinyPngSonataMediaBundle\Client\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class TinyPngCheckTest
 *
 * @author Fabien Chaillou <fabien.chaillou@ekino.com>
 */
class TinyPngCheckTest extends TestCase
{
    /**
     * @var TinyPngCheck
     */
    private $check;

    /**
     * @var ClientInterface|MockObject
     */
    private $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->check  = new TinyPngCheck(500, $this->client);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('TinyPNG: count of compressions made this month.', $this->check->getLabel());
    }

    public function testCheckSuccessLimitNotDepacedTest(): void
    {
        $this->client->expects($this->once())->method('getCompressionCount')->willReturn(12);

        $result = $this->check->check();
        $this->assertInstanceOf('ZendDiagnostics\Result\Success', $result);
        $this->assertSame('Count of compressions made for this month: 12.', $result->getMessage());
    }

    public function testCheckSuccessLimitDepacedTest(): void
    {
        $this->client->expects($this->once())->method('getCompressionCount')->willReturn(600);

        $result = $this->check->check();
        $this->assertInstanceOf('ZendDiagnostics\Result\Failure', $result);
        $this->assertSame('Max count of compressions reached! 600 images compressed this month, 500 allowed.', $result->getMessage());
    }
}
