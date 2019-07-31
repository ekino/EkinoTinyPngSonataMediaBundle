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

interface ClientInterface
{
    /**
     * @param string $inputPath
     * @param string $outputPath
     * @param bool $overwrite
     */
    public function optimize(string $inputPath, string $outputPath, bool $overwrite = true): void;
}
