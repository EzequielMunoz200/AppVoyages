<?php

namespace App\EventSubscriber;

use App\Controller\TokenAuthenticatedController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class TokenSubscriber implements EventSubscriberInterface
{
    private $tokens;

    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }
        /* $host = $event->getRequest()->getHost();
        dd($host); */
        if ($event->getRequest()->getClientIp() === '127.0.0.1') {
            // mark the request as having passed token authentication
            $event->getRequest()->attributes->set('token', '5cUu2iLaOpIWuoVgz49z');
        }

        //dd($event->getRequest());
        if ($controller instanceof TokenAuthenticatedController) {
            $token = $event->getRequest()->attributes->get('token');
            //dd($event->getRequest()->query->get('token'));
            if (!in_array($token, $this->tokens)) {
                throw new AccessDeniedHttpException('This action needs a valid token!');
            }

            // mark the request as having passed token authentication
            //$event->getRequest()->attributes->set('token', '5cUu2iLaOpIWuoVgz49z');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
