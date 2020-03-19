<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Entity\Verbe;

/**
 * @Route("/langues")
 */
class AnglaisController extends GatewayController
{
    /**
     * @Route("/anglais", name="anglais_index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $options = array(
            'controller_name' => 'AnglaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini')
        );
        return $this->render('anglais/index.html.twig', $options);
    }

    /**
     * @Route("/anglais/verbes/{niveau}", methods={"GET","POST"}, name="anglais_verbes")
     */
    public function verbes(int $niveau)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $options = array(
            'controller_name' => 'AnglaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini'),
            'link' => '/langues/anglais/verbes/'.$niveau.'/run'
        );

        return $this->render('anglais/start.html.twig', $options);
    }

    /**
     * @Route("/anglais/verbes/{niveau}/run", methods={"GET","POST"}, name="anglais_run")
     */
    public function run(int $niveau)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $game_name = 'anglais_'.$niveau;
        $game = $this->session->get($game_name);

        $step = $this->request->get('step');
        jlog(var_export($this->request->get('inf'), true));
        if ($step == null) {
            if (!isset($game['step'])) {
                $this->queryManager->setCatalog('build/data/anglais.ini');
                $data = $this->queryManager->execRawQuery('getVerbesID', array('level' => $niveau));
                $nb_verbs = count($data);
                $i = 0;
                while ($i<10) {
                    $rand = rand(0, $nb_verbs);
                    if (isset($tmp[$rand])) continue;
                    $tmp[$rand] = $rand;
                    $i++;
                }
                $verbes_liste = join(',', $tmp);
                $game['verbes'] = $this->queryManager->execRawQuery('getSelectedVerbes', array('verbes_liste' => $verbes_liste));
                $game['step'] = 1;
                $game['reponses'] = array();
                $game['result'] = array();
        
                $this->session->set($game_name, $game);
            }
            $options = array(
                'controller_name' => 'AnglaisController',
                'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
                'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini'),
                'link' => 'langues/anglais/verbes/'.$niveau.'/run',
                'step' => $game['step'],
                'niveau' => $niveau,
                'verbe' => $game['verbes'][$game['step']-1]
            );
    
            return $this->render('anglais/run.html.twig', $options);
        }
        else {
            $game['reponses'][$game['step']]['inf'] = $this->request->get('inf');
            $game['reponses'][$game['step']]['pret'] = $this->request->get('pret');
            $game['reponses'][$game['step']]['ps'] = $this->request->get('ps');

            $result = true;
            if ($game['verbes'][$game['step']-1]['infinitif'] !== $this->request->get('inf')) $result = false;
            if ($game['verbes'][$game['step']-1]['form1'] !== $this->request->get('pret')) $result = false;
            if ($game['verbes'][$game['step']-1]['form2'] !== $this->request->get('ps')) $result = false;

            $game['step'] += 1;
            $tmp = array('verbe' => $game['verbes'][$game['step']-1], 'step' => $game['step']);
            $this->session->set($game_name, $game);

            $response = new Response(json_encode($tmp));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

}
