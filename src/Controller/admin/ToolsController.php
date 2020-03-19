<?php

namespace App\Controller\admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;

use App\Service\BSMenuGenerator;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/tools")
 */
class ToolsController extends AbstractController
{
    private $session;
    private $menu;

    public function __construct(SessionInterface $session, BSMenuGenerator $menu)
    {
        $this->session = $session;
        $this->menu = $menu;
    }

    /**
     * @Route("/reset", name="sess_reset")
     */
    public function reset()
    {
        $this->session->invalidate();
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/session", name="sess_disp")
     */
    public function sess()
    {
        $data = $this->session->get('filters');
        return $this->render('admin/tools/index.html.twig', [
            'data' => $data,
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini')
        ]);
    }
}
