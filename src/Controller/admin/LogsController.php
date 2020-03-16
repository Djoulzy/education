<?php

namespace App\Controller\admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Entity\Logs;
use App\Form\LogsType;
use App\Repository\LogsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\QueryManager;
use App\Service\BSMenuGenerator;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/app/admin/log")
 */
class LogsController extends AbstractController
{
    private $menu;
    private $queryManager;

    public function __construct(BSMenuGenerator $menu, QueryManager $queryManager)
    {
        $this->menu = $menu;
        $this->queryManager = $queryManager;
        $this->queryManager->setCatalog('build/data/logs.ini');
    }

    /**
     * @Route("/", name="logs_index", methods={"GET"})
     */
    public function index(LogsRepository $logsRepository): Response
    {
        $data = $this->queryManager->execRawQuery('AccountAccess', []);

        return $this->render('admin/logs/index.html.twig', [
            'logs' => $data,
            'topmenu' => $this->menu->renderTopMenu('build/data/menu.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/sm_admin.ini'),
        ]);
    }

    /**
     * @Route("/{id}", name="logs_show", methods={"GET"})
     */
    public function show(string $id): Response
    {
        $params = array(
            'id' => $id
        );

        $data = $this->queryManager->execRawQuery('UserLog', $params);
        $user = $data[0]['username'];

        return $this->render('admin/logs/show.html.twig', [
            'user' => $user,
            'logs' => $data,
            'topmenu' => $this->menu->renderTopMenu('build/data/menu.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/sm_admin.ini'),
        ]);
    }
}
