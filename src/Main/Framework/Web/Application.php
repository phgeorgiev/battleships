<?php

namespace Framework\Web;

use Battleships\Game\Battlefield;
use Battleships\Helper\ShotsManager;
use Event\ResponseEvent;
use Framework\ApplicationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Application implements ApplicationInterface
{

    /**
     * @var ContainerBuilder
     */
    private $container;
    /**
     * @var UrlMatcher
     */
    private $matcher;
    /**
     * @var ControllerResolver
     */
    private $resolver;

    /**
     * @var
     */
    private $routes;

    /**
     * @var bool
     */
    private $isRoutesDefined = false;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @param ContainerBuilder $container
     * @param EventDispatcher $dispatcher
     */
    public function __construct(ContainerBuilder $container, EventDispatcher $dispatcher)
    {
        $this->container = $container;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Run the application
     */
    public function run()
    {
        $request = Request::createFromGlobals();

        $context = new RequestContext();
        $context->fromRequest($request);

        if (!$this->isRoutesDefined) {
            $this->defineRoutes();
        }

        $this->matcher = new UrlMatcher($this->routes, $context);
        $this->resolver = new ControllerResolver();

        $this->dispatcher->addListener('post.controller', array($this->container->get('listener.post_controller'), 'onPostController'));

        $response = $this->handle($request);
        $response->send();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $session = new Session(new NativeSessionStorage(array('cookie_lifetime' => 60 * 60)));
            $session->start();

            $request->setSession($session);

            $controllerParams = $this->resolver->getController($request);
            $arguments = $this->resolver->getArguments($request, $controllerParams);

            /** @var Controller $controller */
            $controller = $controllerParams[0];
            $controller->setContainer($this->container);

            $response = call_user_func_array($controllerParams, $arguments);

            $this->dispatcher->dispatch('post.controller', new ResponseEvent($session));

            return $response;
        } catch (ResourceNotFoundException $e) {
            return new Response('Not Found', 404);
        } catch (\Exception $e) {
            return new Response('An error occurred', 500);
        }
    }

    /**
     * Define application routes
     */
    public function defineRoutes()
    {
        $this->routes = new RouteCollection();

        $this->routes->add(
            'index',
            new Route(
                '/', [
                    '_controller' => array($this->container->get('controller.http'), 'indexAction'),
                ]
            )
        );

        $this->isRoutesDefined = true;
    }
}