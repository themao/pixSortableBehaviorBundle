<?php

namespace Pix\SortableBehaviorBundle\Twig;

use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of ObjectPositionExtension
 * 
 * @author Volker von Hoesslin <volker.von.hoesslin@empora.com>
 */
class ObjectPositionExtension extends \Twig_Extension
{
    const NAME = 'sortableObjectPosition';
    const FIRST_POSITION_FUNCTION_NAME = 'firstPosition';

    /**
     * PositionHandler
     */
    private $positionService;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(PositionHandler $positionService, ContainerInterface $container)
    {
        $this->positionService = $positionService;
        $this->container = $container;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return self::NAME;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(self::NAME,
                function ($entity)
                {
                    $getter = sprintf('get%s', ucfirst($this->positionService->getPositionFieldByEntity($entity)));
                    return $entity->{$getter}();
                }
            ),
            new \Twig_SimpleFunction(self::FIRST_POSITION_FUNCTION_NAME,
                function ()
                {
                    return $this->container->getParameter('start_count');
                }
            )
        );
    }
}
