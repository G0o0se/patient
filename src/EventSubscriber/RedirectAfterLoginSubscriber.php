<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class RedirectAfterLoginSubscriber implements EventSubscriberInterface
{
    private $router;

    private $redirectResponse;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $this->redirectResponse = new RedirectResponse($this->router->generate('admin'));
        } elseif (in_array(User::ROLE_PATIENT, $user->getRoles())) {
            $this->redirectResponse = new RedirectResponse($this->router->generate('patient_information'));
        } elseif (in_array(User::ROLE_DOCTOR, $user->getRoles())) {
            $this->redirectResponse = new RedirectResponse($this->router->generate('doctor_patients_list'));
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if ($this->redirectResponse) {
            $event->setResponse($this->redirectResponse);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}