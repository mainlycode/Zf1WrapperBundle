<?php

namespace MainlyCode\Zf1WrapperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Zend_Registry;

class DefaultController extends Controller
{
    /**
     * indexAction
     *
     * @param string $url
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($url)
    {
        // pass Dependency Injection Container
        Zend_Registry::set('dic', $this->container);

        $rootDir   = $this->get('kernel')->getRootDir();
        $bootstrap = $this->container->getParameter('zf1wrapper_bootstrap_path');

        // capture content from legacy application
        ob_start();
        include $rootDir . '/' . $bootstrap;
        $content = ob_get_clean();

        // exit on Location header
        $matches = array_filter(headers_list(), function ($v) { return 'Location:' == substr($v, 0, 9); });
        if (count($matches) >0) {
            exit;
        }

        return new Response($content);
    }
}
