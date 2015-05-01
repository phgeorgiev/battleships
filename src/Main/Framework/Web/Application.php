<?php

namespace Framework\Web;

use Battleships\Game\Battlefield;
use Battleships\Helper\ShotsManager;
use Framework\ApplicationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
use Symfony\Component\Serializer\Serializer;

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
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
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

            /** @var Battlefield $battlefield */
            $battlefield = $this->container->get('battlefield');
            /** @var ShotsManager $shotsManager */
            $shotsManager = $this->container->get('shots_manager');

            $fleet = $battlefield->getFleet();
            $shots = $shotsManager->getAllShots();
            $hits = $shotsManager->getHits();

            $session->set('fleet', serialize($fleet));
            $session->set('shots', serialize($shots));
            $session->set('hits', serialize($hits));

            return $response;
        } catch (ResourceNotFoundException $e) {
            return new Response('Not Found', 404);
        } catch (\Exception $e) {
            var_dump($e);exit;
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