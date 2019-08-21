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

namespace Ekino\TinyPngSonataMediaBundle\Check;

use Ekino\TinyPngSonataMediaBundle\Client\ClientInterface;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\ResultInterface;
use ZendDiagnostics\Result\Success;

/**
 * Class TinyPngCheck
 */
final class TinyPngCheck implements CheckInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var int
     */
    private $maxCompressionCountByMonth;

    /**
     * TinyPngCheck constructor.
     *
     * @param int             $maxCompressionCountByMonth
     * @param ClientInterface $client
     */
    public function __construct(int $maxCompressionCountByMonth, ClientInterface $client)
    {
        $this->maxCompressionCountByMonth = $maxCompressionCountByMonth;
        $this->client                     = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function check(): ResultInterface
    {
        $compressionCount = $this->client->getCompressionCount();

        if ($compressionCount >= $this->maxCompressionCountByMonth) {
            return new Failure(sprintf('Max count of compressions reached! %d images compressed this month, %d allowed.', $compressionCount, $this->maxCompressionCountByMonth));
        }

        return new Success(sprintf('Count of compressions made for this month: %d.', $compressionCount));
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return 'TinyPNG: count of compressions made this month.';
    }
}
