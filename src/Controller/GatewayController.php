<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

use App\Service\CheckPermissions;
use App\Service\BSMenuGenerator;

class GatewayController extends AbstractController
{
    protected $perms;
    protected $request;
    protected $session;
    protected $menu;

    public function __construct(CheckPermissions $perms, RequestStack $request, SessionInterface $session, BSMenuGenerator $menu)
    {
        $this->perms = $perms;
        $this->request = $request->getCurrentRequest();
        $this->session = $session;
        $this->menu = $menu;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $options = array(
            'controller_name' => 'GatewayController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
        );

        return $this->render('gateway/index.html.twig', $options);
    }
}
