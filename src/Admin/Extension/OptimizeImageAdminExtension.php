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

namespace Ekino\TinyPngSonataMediaBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class OptimizeImageAdminExtension.
 *
 * @author William JEHANNE <william.jehanne@ekino.com>
 */
final class OptimizeImageAdminExtension extends AbstractAdminExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureActionButtons(AdminInterface $admin, $list, $action, $object)
    {
        $list = parent::configureActionButtons($admin, $list, $action, $object);

        $list['custom_action'] = [
            'template' => '@EkinoTinyPngSonataMedia/OptimizeImageAdmin/button.html.twig',
        ];

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function configureRoutes(AdminInterface $admin, RouteCollection $collection)
    {
        $collection->add('optimize',
            'optimize-image/{id}',
            [],
            ['id' => '\d+']
        );
    }
}
