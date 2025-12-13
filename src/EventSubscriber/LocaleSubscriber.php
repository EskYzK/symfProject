<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct(string $defaultLocale = 'fr')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Essaie de récupérer la locale en session
        if ($locale = $request->getSession()->get('_locale')) {
            $request->setLocale($locale);
        } else {
            // Sinon utilise la langue par défaut
            $request->setLocale($this->defaultLocale);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Doit s'exécuter avant le Routeur (priorité 20)
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}