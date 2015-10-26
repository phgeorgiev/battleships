<?php

namespace Framework\Web;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class Controller
{

    /**
     * @var ContainerBuilder
     */
    protected $container;

    static $sourcePath = '/src/Main/';

    /**
     * @param ContainerBuilder $container
     */
    public function setContainer(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function render($template, array $params = array())
    {
        $documentRoot = $this->container->getParameter('document_root');
        $templatePath = $documentRoot . self::$sourcePath . $template;

        if (!file_exists($templatePath)) {
            throw new \Exception("Template $template is missing!");
        }

        /** @var Session $session */
        $session = $this->container->get('session');
        $params['flashMessage'] = $session->getFlashBag()->get('notice');

        ob_start();
        extract($params, EXTR_SKIP);
        include $templatePath;

        return new Response(ob_get_clean());
    }
}