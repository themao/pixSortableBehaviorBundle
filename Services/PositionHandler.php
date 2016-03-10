<?php
/*
 * This file is part of the pixSortableBehaviorBundle.
 *
 * (c) Nicolas Ricci <nicolas.ricci@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pix\SortableBehaviorBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class PositionHandler
{
    /** @var ContainerInterface */
    protected $container;

    protected $positionField;

    abstract public function getLastPosition($entity);

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $positionField
     */
    public function setPositionField($positionField)
    {
        $this->positionField = $positionField;
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function getPositionFieldByEntity($entity)
    {
        if (is_object($entity)) {
            $entity = \Doctrine\Common\Util\ClassUtils::getClass($entity);
        }
        if (isset($this->positionField['entities'][$entity])) {
            return $this->positionField['entities'][$entity];
        } else {
            return $this->positionField['default'];
        }
    }

    /**
     * @param $object
     * @param $position
     * @param $lastPosition
     *
     * @return int
     */
    public function getPosition($object, $position, $lastPosition)
    {
        $getter = sprintf('get%s', ucfirst($this->getPositionFieldByEntity($object)));
        $newPosition = 0;
        $startCount = $this->container->getParameter('start_count');

        switch ($position) {
            case 'up' :
                if ($object->{$getter}() > 0) {
                    $newPosition = $object->{$getter}() - 1;
                }
                break;

            case 'down':
                if ($object->{$getter}() < $lastPosition) {
                    $newPosition = $object->{$getter}() + 1;
                }
                break;

            case 'top':
                if ($object->{$getter}() > $startCount) {
                    $newPosition = $startCount;
                }
                break;

            case 'bottom':
                if ($object->{$getter}() < $lastPosition) {
                    $newPosition = $lastPosition;
                }
                break;
        }

        return $newPosition;
    }
}
