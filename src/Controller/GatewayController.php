<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

use App\Service\CheckPermissions;
use App\Service\BSMenuGenerator;
use App\Service\QueryManager;

use App\Entity\Score;

class GatewayController extends AbstractController
{
    protected $perms;
    protected $request;
    protected $session;
    protected $menu;
    protected $queryManager;
    protected $games;

    public function __construct(CheckPermissions $perms, RequestStack $request, SessionInterface $session, BSMenuGenerator $menu, QueryManager $queryManager)
    {
        $this->perms = $perms;
        $this->request = $request->getCurrentRequest();
        $this->session = $session;
        $this->menu = $menu;
        $this->queryManager = $queryManager;

        $this->games = array(
            1 => 'Anglais - Verbes irréguliers - niv1',
            2 => 'Anglais - Verbes irréguliers - niv2',
        );
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

    protected function saveContext($score, $game)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $tmp = new Score;
        $tmp->setPlayer($this->getUser());
        $tmp->setDate(new \DateTime('NOW'));
        $tmp->setGame($game);
        $tmp->setValue($score);

        $entityManager->persist($tmp);
        $entityManager->flush();
    }

    protected function run(int $game_num, int $nb_quest, int $max_points,
        string $route, string $template, string $ss_menu)
    {
        $game_name = 'game_'.$game_num;
        $game = $this->session->get($game_name);

        $step = $this->request->get('step');
        if ($step == null) {
            if (!isset($game['step'])) {

                $game['questions'] = $this->initQuestions($game_num);
                $game['step'] = 1;
                $game['reponses'] = array();
                $game['results'] = array();
                $game['points'] = $max_points;
        
                $this->session->set($game_name, $game);
            }

            if ($game['step'] < $nb_quest) $questions = $game['questions'][$game['step']-1];
            else $questions = $game['questions'][$nb_quest-1];
            $options = array(
                'controller_name' => 'GatewayController',
                'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
                'sidemenu' => $this->menu->renderSideMenu($ss_menu),
                'link' => $route.$game_num.'/run',
                'redirect' => '/'.$route.$game_num.'/correction',
                'step' => $game['step'],
                'nb_quest' => $nb_quest,
                'game_name' => $this->games[$game_num],
                'questions' => $questions,
                'results' => json_encode($game['results'])
            );

            return $this->render($template, $options);
        }
        else {
            $game['reponses'][$game['step']] = $this->storeAnswer();

            $lost = $this->computeLostPoints($game['questions'][$game['step']-1], $game['reponses'][$game['step']]);
            $game['points'] += $lost;
            $game['results'][$game['step']] = true;
            if ($lost < 0) $game['results'][$game['step']] = false;

            $game['step'] += 1;
            $this->session->set($game_name, $game);

            if ($game['step'] > $nb_quest) {
                $pts = round($game['points'] / ($max_points)*100);
                $this->saveContext($pts, $game_num);
                $tmp = array('questions' => $game['questions'][$nb_quest-1], 'step' => $game['step'], 'results' => $game['results']);
            }
            else
                $tmp = array('questions' => $game['questions'][$game['step']-1], 'step' => $game['step'], 'results' => $game['results']);

            $response = new Response(json_encode($tmp));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    protected function correction(int $game_num, int $nb_quest, int $max_points,
        string $route, string $template, string $ss_menu)
    {
        $game = $this->session->get('game_'.$game_num);

        $options = array(
            'controller_name' => 'GatewayController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu($ss_menu),
            'game_name' => $this->games[$game_num],
            'questions' => $game['questions'],
            'reponses' => $game['reponses'],
            'note' => round(($game['points'] / $max_points)*20)
        );

        return $this->render($template, $options);
    }
}
