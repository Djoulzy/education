<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Verbe;
use App\Entity\Score;

/**
 * @Route("/langues")
 */
class AnglaisController extends GatewayController
{
    const NB_QUEST = 10;
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
        $i = 0;
        while ($i<10) {
            $rand = rand(0, 12);
            if (isset($tmp[$rand])) continue;
            $tmp[$rand] = $rand;
            $i++;
        }
        dump($tmp);

        $options = array(
            'controller_name' => 'AnglaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini'),
            'link' => '/langues/anglais/verbes/'.$niveau.'/run'
        );

        return $this->render('anglais/start.html.twig', $options);
    }

    private function saveContext($score)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $tmp = new Score;
        $tmp->setPlayer($this->getUser());
        $tmp->setDate(new \DateTime('NOW'));
        $tmp->setGame(1);
        $tmp->setValue($score);

        jlog(var_export($this->getUser(), true));

        $entityManager->persist($tmp);
        $entityManager->flush();
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
        if ($step == null) {
            if (!isset($game['step'])) {
                $this->queryManager->setCatalog('build/data/anglais.ini');
                $data = $this->queryManager->execRawQuery('getVerbesID', array('level' => $niveau));
                dump($data);
                $nb_verbs = count($data);
                dump($nb_verbs);
                $i = 0;
                while ($i < self::NB_QUEST) {
                    $rand = rand(0, $nb_verbs-1);
                    if (isset($tmp[$rand])) continue;
                    $tmp[$rand] = $rand;
                    $i++;
                }
                $verbes_liste = join(',', $tmp);
                $game['verbes'] = $this->queryManager->execRawQuery('getSelectedVerbes', array('verbes_liste' => $verbes_liste));
                $game['step'] = 1;
                $game['reponses'] = array();
                $game['results'] = array();
                $game['points'] = self::NB_QUEST * 3;
        
                $this->session->set($game_name, $game);
            }
            jlog(var_export($game['step'], true));
            if ($game['step'] < self::NB_QUEST) $verbe = $game['verbes'][$game['step']-1];
            else $verbe = $game['verbes'][self::NB_QUEST-1];
            $options = array(
                'controller_name' => 'AnglaisController',
                'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
                'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini'),
                'link' => 'langues/anglais/verbes/'.$niveau.'/run',
                'redirect' => '/langues/anglais/verbes/'.$niveau.'/correction',
                'step' => $game['step'],
                'nb_quest' => self::NB_QUEST,
                'niveau' => $niveau,
                'verbe' => $verbe,
                'results' => json_encode($game['results'])
            );
    
            return $this->render('anglais/run.html.twig', $options);
        }
        else {
            $game['reponses'][$game['step']]['inf'] = $this->request->get('inf');
            $game['reponses'][$game['step']]['pret'] = $this->request->get('pret');
            $game['reponses'][$game['step']]['ps'] = $this->request->get('ps');

            $result = true;
            if ($game['verbes'][$game['step']-1]['infinitif'] !== $this->request->get('inf')) { $result = false; $game['points']--; }
            if ($game['verbes'][$game['step']-1]['form1'] !== $this->request->get('pret')) { $result = false; $game['points']--; }
            if ($game['verbes'][$game['step']-1]['form2'] !== $this->request->get('ps')) { $result = false; $game['points']--; }
            $game['results'][$game['step']] = $result;

            $game['step'] += 1;
            $this->session->set($game_name, $game);
            jlog(var_export($game['step'], true));

            if ($game['step'] > self::NB_QUEST) {
                $pts = round($game['points'] / (self::NB_QUEST * 3)*100);
                $this->saveContext($pts);
                $tmp = array('verbe' => $game['verbes'][self::NB_QUEST-1], 'step' => $game['step'], 'results' => $game['results']);
            }
            else
                $tmp = array('verbe' => $game['verbes'][$game['step']-1], 'step' => $game['step'], 'results' => $game['results']);

            $response = new Response(json_encode($tmp));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * @Route("/anglais/verbes/{niveau}/correction", methods={"GET"}, name="anglais_correct")
     */
    public function correction(int $niveau)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $game_name = 'anglais_'.$niveau;
        $game = $this->session->get($game_name);

        $options = array(
            'controller_name' => 'AnglaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini'),
            'niveau' => $niveau,
            'verbes' => $game['verbes'],
            'reponses' => $game['reponses'],
            'note' => round($game['points'] / (self::NB_QUEST * 3)*20)
        );

        return $this->render('anglais/correction.html.twig', $options);
    }
}
