<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/langues")
 */
class AnglaisController extends GatewayController
{
    /**
     * @Route("/anglais/verbes", name="anglais")
     */
    public function verbes()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $options = array(
            'controller_name' => 'BOGatewayController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini')
        );

        return $this->render('anglais/easy.html.twig', $options);
    }
}
