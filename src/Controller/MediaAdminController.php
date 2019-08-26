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

namespace Ekino\TinyPngSonataMediaBundle\Controller;

use Ekino\TinyPngSonataMediaBundle\OptimizeImageTrait;
use Sonata\MediaBundle\Controller\MediaAdminController as BaseMediaAdminController;

/**
 * Class MediaAdminController.
 *
 * @author William JEHANNE <william.jehanne@ekino.com>
 */
class MediaAdminController extends BaseMediaAdminController
{
    use OptimizeImageTrait;
}
