<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

// Creation de l'EventSubscriber 
class LogoutEventSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;
    private $flashbag;

    public function __construct(UrlGeneratorInterface $urlGenerator, FlashBagInterface $flashbag)
    {
        $this->urlGenerator = $urlGenerator;
        $this->flashbag = $flashbag;
    }

    public function onLogoutEvent(LogoutEvent $event): void
    {
        /*
            $this->addFlash() => on l'utilise lorsqu'on est dans un controller
        */

        // Afficher le message flash 
        $this->flashbag->add(
            'success',
            'Bye bye ' . $event->getToken()->getUser()->getUserIdentifier() . '!',
        );

        // Route de redirection
        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));

    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
