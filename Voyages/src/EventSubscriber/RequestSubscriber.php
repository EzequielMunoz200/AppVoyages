<?php
//https://stackoverflow.com/questions/51460188/guard-authenticator-in-symfony-4
//https://symfony.com/doc/current/security/form_login_setup.html#redirecting-to-the-last-accessed-page-with-targetpathtrait
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RequestSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    private $session;
    private $urlGenerator;

    public function __construct(SessionInterface $session, UrlGeneratorInterface $urlGenerator)
    {
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (
            !$event->isMasterRequest()
            || $request->isXmlHttpRequest()
            || 'app_login' === $request->attributes->get('_route')
        ) {
            return;
        }

        //the route /api/v1/translate (POST) is the last request, but I wan't this. 
        //I want to go on the last city page if I was there.  
        if (($request->getUri() === '/api/v1/translate/' || !$this->session->get('lastCityVisited')) &&  $request->getUri() !== '/api/v1/image/') {
            $this->saveTargetPath($this->session, 'main', $request->getUri());
        } else {
            $this->saveTargetPath($this->session, 'main', $this->session->get('lastCityVisited'));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest']
        ];
    }
}
