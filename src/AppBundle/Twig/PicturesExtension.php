<?php
namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PicturesExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getFrames', array($this, 'getFrames')),
            new \Twig_SimpleFunction('getSizes', array($this, 'getSizes')),
            new \Twig_SimpleFunction('filesize', 'getimagesize'),
        );
    }

    public function getFrames()
    {
        return $this->container->get('pictures.service')->getFrames();
    }

    public function getSizes()
    {
        return $this->container->get('pictures.service')->getSizes();
    }

}