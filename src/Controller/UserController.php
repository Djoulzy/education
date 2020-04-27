<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Service\ChartWrapper;

class UserController extends GatewayController
{
    static public function Standard($data)
    {
        $php_values = array();
		foreach($data as $vals) {
            // $php_values[$vals['date']]['date'] = $vals['date'];
            $php_values[$vals['label']]['s_'.$vals['serie']] = $vals['value'];
            $php_values[$vals['label']]['category'] = $vals['label'];
            $series['s_'.$vals['serie']] = true;
        }

        $res = array(
            'series' => array_keys($series),
            'values' => array_values($php_values)
        );

        $vals = json_encode($res);
        // $vals = json_encode($php_values);
        return $vals;
    }

    /**
     * @Route("/chart/{name}/{query}/data.json", name="chart_update")
     */
    public function updateChartData(string $name, string $query)
    {
        $this->queryManager->setCatalog('build/data/queries.ini');

        $params = array(
            'user' => $this->getUser()->getId(),
        );
        $data = $this->queryManager->execRawQuery($query, $params);

        $tmp = call_user_func('App\\Controller\\UserController::Standard', $data);
        $response = new Response($tmp);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/user", name="user")
     */
    public function progression(ChartWrapper $chart)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $title = "Progression";
        $widget = $chart->create('dateLine', 'getProgression', 'kelly', $title, 'dateLineFull', true, true);

        $options = array(
            'controller_name' => 'UserController',
            'topmenu' => $this->menu->renderTopMenu('build/data/menus/home.ini'),
            'sidemenu' => $this->menu->renderSideMenu('build/data/menus/ss_user.ini'),
            'widgets' => $widget
        );

        return $this->render('user/index.html.twig', $options);
    }
}
