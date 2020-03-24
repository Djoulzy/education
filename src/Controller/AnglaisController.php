<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Verbe;

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

        $options = array(
            'controller_name' => 'AnglaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_anglais.ini'),
            'link' => '/langues/anglais/verbes/'.$niveau.'/run'
        );

        return $this->render('anglais/start.html.twig', $options);
    }

    protected function initQuestions(int $game): array
    {
        $this->queryManager->setCatalog('build/data/queries.ini');
        $data = $this->queryManager->execRawQuery('getVerbesID', array('level' => $game));
        $nb_verbs = count($data);
        $i = 0;
        while ($i < self::NB_QUEST) {
            $rand = rand(0, $nb_verbs-1);
            if (isset($tmp[$rand])) continue;
            $tmp[$rand] = $data[$rand]['id'];
            $i++;
        }
        $verbes_liste = join(',', $tmp);
        return $this->queryManager->execRawQuery('getSelectedVerbes', array('verbes_liste' => $verbes_liste));
    }

    protected function storeAnswer()
    {
        $tmp = array(
            'inf' => $this->request->get('inf'),
            'pret' => $this->request->get('pret'),
            'ps' => $this->request->get('ps')
        );
        return $tmp;
    }

    protected function computeLostPoints(array $soluce, array $answer): int
    {
        $lost = 0;
        if ($soluce['infinitif'] !== $answer['inf']) $lost--;
        if ($soluce['form1'] !== $answer['pret']) $lost--;
        if ($soluce['form2'] !== $answer['ps']) $lost--;
        return $lost;
    }

    /**
     * @Route("/anglais/verbes/{game}/run", methods={"GET","POST"}, name="anglais_run")
     */
    public function start(int $game)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->run($game, self::NB_QUEST, self::NB_QUEST*3,
            'langues/anglais/verbes/',
            'anglais/run.html.twig',
            'build/data/menus/ss_anglais.ini');
    }

    /**
     * @Route("/anglais/verbes/{game}/correction", methods={"GET"}, name="anglais_correct")
     */
    public function end(int $game)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->correction($game, self::NB_QUEST, self::NB_QUEST*3,
            'langues/anglais/verbes/',
            'anglais/correction.html.twig',
            'build/data/menus/ss_anglais.ini');
    }
}
