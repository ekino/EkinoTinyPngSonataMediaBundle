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

namespace Ekino\TinyPngSonataMediaBundle\Client;

use Tinify\Source;
use Tinify\Tinify;

/**
 * Class Client
 *
 * @author Benoit Mazière <benoit.maziere@ekino.com>
 */
final class Client implements ClientInterface
{
    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        Tinify::setKey($apiKey);
    }

    /**
     * {@inheritdoc}
     */
    public function optimize(string $inputPath, string $outputPath, bool $overwrite = true): void
    {
        if (!$overwrite && file_exists($outputPath)) {
            throw new \RuntimeException(sprintf('The file %s already exists and the overwrite option is false', $outputPath));
        }

        $this->doOptimize($inputPath, $outputPath);
    }

    /**
     * @param string $inputPath
     * @param string $outputPath
     */
    protected function doOptimize(string $inputPath, string $outputPath): void
    {
        Source::fromFile($inputPath)->toFile($outputPath);
    }
}
