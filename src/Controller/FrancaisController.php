<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Verbe;
use App\Entity\VerbeTemplate;

/**
 * @Route("/langues")
 */
class FrancaisController extends GatewayController
{
    const NB_QUEST = 5;
    const MAX_PT = 30;
    const TEMPS = array(
        1 => 'Indicatif Présent',
        2 => 'Indicatif Futur',
        3 => 'Indicatif Imparfait',
        4 => 'Indicatif Passé Simple',
        5 => 'Conditionel Présent',
        6 => 'Subjonctif Présent',
        7 => 'Subjonctif Imparfait'
    );

    /**
     * @Route("/francais", name="francais_index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $options = array(
            'controller_name' => 'FrancaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_francais.ini')
        );
        return $this->render('francais/index.html.twig', $options);
    }

    private function getTemps($node)
    {
        foreach($node->p as $term) {
            $tmp[] = (string)($term->i);
        }
        return $tmp;
    }

    /**
     * @Route("/francais/import", methods={"GET","POST"}, name="francais_import")
     */
    public function import()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $repo = $entityManager->getRepository(VerbeTemplate::class);
        
        if (file_exists('../tmp/conjugation-fr.xml')) {
            $xml = simplexml_load_file('../tmp/conjugation-fr.xml');
        }

        foreach($xml->template as $index => $template) {
            $name = ((string)($template->attributes()['name']));
            $inf = ((string)($template->infinitive->{'infinitive-present'}->p->i));

            // foreach($template as $nom_mode => $mode) {
            //     if ($nom_mode == 'infinitive') continue;
            //     // foreach($mode as $temps) {
            //         foreach($mode as $nom_temps => $temps) {
            //             jlog($nom_temps);
            //             $data[$nom_mode][$nom_temps] = $this->getTemps($temps);
            //         }
            //     // }
            // }
            $data[1] = $this->getTemps($template->indicative->present);
            $data[2] = $this->getTemps($template->indicative->future);
            $data[3] = $this->getTemps($template->indicative->imperfect);
            $data[4] = $this->getTemps($template->indicative->{'simple-past'});
            $data[5] = $this->getTemps($template->conditional->present);
            $data[6] = $this->getTemps($template->subjunctive->present);
            $data[7] = $this->getTemps($template->subjunctive->imperfect);

            $repo->insert($name, $inf, $data);
        }

        if (file_exists('../tmp/verbs-fr.xml')) {
            $xml = simplexml_load_file('../tmp/verbs-fr.xml');
        }

        $repo = $entityManager->getRepository(Verbe::class);
        foreach($xml as $index => $verbs) {
            $nom = (string)($verbs->i);
            $temp = (string)($verbs->t);
            $repo->insert('FR', $nom, $nom, $temp, '', 0);
        }

        $options = array(
            'controller_name' => 'FrancaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_francais.ini'),
        );

        return $this->render('francais/test.html.twig', $options);
    }

    protected function initQuestions(int $game): array
    {
        $level = '';
        $max = count(self::TEMPS);
        if ($game == 10) {
            $level = "and form1='aim:er'";
            $max = 4;
        }
        if ($game == 11) $max = 4;
        $this->queryManager->setCatalog('build/data/queries.ini');
        $data = $this->queryManager->execRawQuery('getVerbesFrancaisID', array('level' => $level));
        $nb_verbs = count($data);
        $i = 0;
        while ($i < self::NB_QUEST) {
            $rand = rand(0, $nb_verbs-1);
            if (isset($tmp[$rand])) continue;
            $tmp[$rand] = $data[$rand]['id'];
            $i++;
        }
        $verbes_liste = join(',', $tmp);
        $res = $this->queryManager->execRawQuery('getSelectedFrVerbes', array('verbes_liste' => $verbes_liste));
        foreach($res as $index => $val) {
            $rand = rand(1, $max);
            $tmp = json_decode($val['data'], true);
            $res[$index]['radical'] = substr($val['fr'], 0, strlen($val['infinitive'])* -1);
            $res[$index]['temps'] = self::TEMPS[$rand];
            $res[$index]['data'] = $tmp[$rand];
        }
        return $res;
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
     * @Route("/francais/verbes/{niveau}", methods={"GET","POST"}, name="francais_verbes")
     */
    public function verbes(int $niveau)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $options = array(
            'controller_name' => 'FrancaisController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_francais.ini'),
            'link' => '/langues/francais/verbes/'.$niveau.'/run'
        );

        return $this->render('francais/start.html.twig', $options);
    }

    /**
     * @Route("/francais/verbes/{game}/run", methods={"GET","POST"}, name="francais_run")
     */
    public function start(int $game)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->run($game, self::NB_QUEST, self::MAX_PT,
            'langues/francais/verbes/',
            'francais/run.html.twig',
            'build/data/menus/ss_francais.ini');
    }

    /**
     * @Route("/francais/verbes/{game}/correction", methods={"GET"}, name="francais_correct")
     */
    public function end(int $game)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->correction($game, self::NB_QUEST, self::MAX_PT,
            'langues/francais/verbes/',
            'francais/correction.html.twig',
            'build/data/menus/ss_francais.ini');
    }
}
