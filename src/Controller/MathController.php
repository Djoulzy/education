<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/math")
 */
class MathController extends GatewayController
{
    const NB_QUEST = 10;
    const MAX_PT = 10;

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

    /**
     * @Route("/calcul/add_sous", methods={"GET","POST"}, name="math_calc_addsous")
     */
    public function add_sous()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $options = array(
            'controller_name' => 'AnglaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_math_calcul.ini'),
            'link1' => '/math/calcul/add_sous/3/run',
            'link2' => '/math/calcul/add_sous/4/run',
            'link3' => '/math/calcul/add_sous/5/run'
        );

        return $this->render('math/start.html.twig', $options);
    }

    protected function initQuestions(int $game): array
    {
        switch($game) {
            case 3:
                for($i=0; $i<self::NB_QUEST; $i++) {
                    $op1 = rand(1, 10);
                    $op2 = rand(1, 10);
                    $tmp[$i]['operation'] = $op1.' + '.$op2;
                    $tmp[$i]['res'] = $op1 + $op2;
                }
            break;
            case 4:
                for($i=0; $i<self::NB_QUEST; $i++) {
                    $op1 = rand(1, 10);
                    $op2 = rand(1, 10);
                    if (rand(0, 1) == 1) {
                        if ($op1 >= $op2) { $tmp[$i]['operation'] = $op1.' - '.$op2; $tmp[$i]['res'] = $op1 - $op2; }
                        else { $tmp[$i]['operation'] = $op2.' - '.$op1; $tmp[$i]['res'] = $op2 - $op1; }
                    } else {
                        $tmp[$i]['operation'] = $op1.' + '.$op2;
                        $tmp[$i]['res'] = $op1 + $op2;
                    }
                }
            break;
            case 5:
                for($i=0; $i<self::NB_QUEST; $i++) {
                    $op1 = rand(1, 100);
                    $op2 = rand(1, 100);
                    if (rand(0, 1) == 1) {
                        if ($op1 >= $op2) { $tmp[$i]['operation'] = $op1.' - '.$op2; $tmp[$i]['res'] = $op1 - $op2; }
                        else { $tmp[$i]['operation'] = $op2.' - '.$op1; $tmp[$i]['res'] = $op2 - $op1; }
                    } else {
                        $tmp[$i]['operation'] = $op1.' + '.$op2;
                        $tmp[$i]['res'] = $op1 + $op2;
                    }
                }
            break;
        }
        return $tmp;
    }

    protected function storeAnswer()
    {
        $tmp = array(
            'res' => $this->request->get('resultat'),
        );
        return $tmp;
    }

    protected function computeLostPoints(array $soluce, array $answer): int
    {
        $lost = 0;
        if ($soluce['res'] != $answer['res']) $lost--;
        return $lost;
    }

    /**
     * @Route("/calcul/add_sous/{game}/run", methods={"GET","POST"}, name="math_calc_addsous_run")
     */
    public function start(int $game)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->run($game, self::NB_QUEST, self::MAX_PT,
            'math/calcul/add_sous/',
            'math/run.html.twig',
            'build/data/menus/ss_math_calcul.ini');
    }

    /**
     * @Route("/calcul/add_sous/{game}/correction", methods={"GET"}, name="math_calc_addsous_correct")
     */
    public function end(int $game)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->correction($game, self::NB_QUEST, self::MAX_PT,
            'math/calcul/add_sous/',
            'math/correction.html.twig',
            'build/data/menus/ss_math_calcul.ini');
    }
}
