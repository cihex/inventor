<?php
namespace AdminBundle\Listeners;

use AdminBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ControllerListener
 * @package AdminBundle\Listeners
 */
class ControllerListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Session
     */
    private $session;

    /**
     * ControllerListener constructor.
     * @param ContainerInterface $container
     * @param Session $session
     */
    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->container = $container;
        $this->session = $session;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $event->getRequest();
        if ($this->container->get('security.token_storage')->getToken() == null) {
            return;
        }

        /**
         * @var User $user
         */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $this->session->getFlashBag()->clear();
        if (method_exists($user, 'isNew') && $user->isNew()) {
            $this->session->getFlashBag()->add('user', 'new');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}