<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/math")
 */
class MathController extends GatewayController
{
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $options = array(
            'controller_name' => 'MathController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_math.ini')
        );
        return $this->render('math/index.html.twig', $options);
    }

    /**
     * @Route("/calcul", name="math_calcul")
     */
    public function calcul()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $options = array(
            'controller_name' => 'MathController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_math_calcul.ini')
        );
        return $this->render('math/index.html.twig', $options);
    }

    public function verbes(int $niveau)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $i = 0;
        // while ($i<10) {
        //     $rand = rand(0, 12);
        //     if (isset($tmp[$rand])) continue;
        //     $tmp[$rand] = $rand;
        //     $i++;
        // }

        $options = array(
            'controller_name' => 'AnglaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini'),
            'link' => '/langues/anglais/verbes/'.$niveau.'/run'
        );

        return $this->render('anglais/start.html.twig', $options);
    }

    public function initQuestions()
    {

    }

    public function start(int $niveau) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $game_name = 'math_'.$niveau;
        $this->run($niveau, $game_name);
    }
}
