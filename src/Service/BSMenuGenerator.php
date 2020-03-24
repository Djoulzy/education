<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\CheckPermissions;

class BSMenuGenerator
{
    private $topm_header = '
        <nav class="navbar sticky-top bg-custom navbar-expand-lg">
            <a class="navbar-brand" href="/"><div id="brand"> </div></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
    ';

    private $theme_switcher = '
            &nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-moon" style="font-size: 20px"></i>
            &nbsp;&nbsp;
            <div class="navbar-nav mr-auto theme-switch-wrapper">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round"></div>
                </label>
            </div>
            &nbsp;&nbsp;
            <i class="fas fa-sun" style="font-size: 20px"></i>
    ';

    private $sidem_header = '
        <nav class="d-flex flex-column flex-grow-1">
            <div class="sidebar-sticky sidemenu">
    ';

    private $sidem_footer = '
            </div>
        </nav>
    ';

    private $request;
    private $idCollection;
    private $perms;

    public function __construct(RequestStack $requestStack, CheckPermissions $perms)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->perms = $perms;
    }

    private function addID($val)
    {
        if ($this->perms->isGranted('ROLE_ADMIN')) {
            if (isset($this->idCollection[$val]))
                die("BAD ID REF: ID ".$val." ALREADY EXISTS");
            else $this->idCollection[$val] = true;
            return '<span class="admin_id">['.$val.']</span>';
        }
        return '';
    }

    public function renderTopMenu($fileName)
    {
        $user = $this->perms->getUser();
        $ini_array = parse_ini_file($fileName, true, INI_SCANNER_TYPED);

        $uri = $this->request->getPathInfo();
        $html = $this->topm_header;
        $ddownCpt = 0;
        foreach($ini_array as $section => $liste) {
            if (isset($liste['desc']['level']) && !$this->perms->isGranted($liste['desc']['level'])) continue;
            $ddownCpt++;
            if (strpos($uri, $liste['desc']['base_url']) !== false) $active = " active";
            else $active = "";
            $html .= '
                    <li class="nav-item dropdown'.$active.'">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDD_'.$ddownCpt.'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $html .= $section;
            $html .= $this->addID($liste['desc']['id']);
            $html .= '                        
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDD_'.$ddownCpt.'" style="z-index: 2000;">';
            foreach($liste as $option => $params) {
                if ($option == 'desc') continue;
                if (($params['level'] == 'admin') && !$this->perms->isGranted('ROLE_ADMIN')) continue;
                switch($params['type']) {
                    case 'header':
                        $html .= '<h6 class="dropdown-header">'.$params['name'].'</h6>';
                        break;
                    case 'divider':
                        $html .= '<div class="dropdown-divider"></div>';
                        break;
                    case 'link':
                        $html .= '
                                    <a class="dropdown-item" href="'.$params['url'].'">';
                        $html .= $params['name'];
                        $html .= $this->addID($params['id']);
                        $html .= '</a>';
                        break;
                }
            }
            $html .= '
            </div>
            </li>';
        }
        $html .= '</ul>';
        $html .= '<ul class="navbar-nav"><li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            '.$user->getNickname().'
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/user_details">Détails</a>
            <a class="dropdown-item" href="/logout">Déconnexion</a>
            </div>
            </li></ul>';
        $html .= '</div>';
        $html .= $this->theme_switcher;
        $html .= '</nav>';
        return $html;
    }

    public function renderSideMenu(string $fileName)
    {
        $ini_array = parse_ini_file($fileName, true, INI_SCANNER_TYPED);

        $uri = $this->request->getPathInfo();
        $html = $this->sidem_header;
        foreach($ini_array as $section => $liste) {
            $html .= '
                <div class="sidebar-heading">
                    '.$section;
            $html .= $this->addID($liste['desc']['id']);
            $html .= '</div>';

            $html .= '
                <ul class="nav sidemenu_list">';
            foreach($liste as $option => $params) {
                if ($option == 'desc') continue;
                switch($params['type']) {
                    case 'header':
                        $html .= '
                        <li class="nav-item sidemenu_disabled"><i class="fas fa-folder-open"></i> '.$params['name'];
                        $html .= $this->addID($params['id']);
                        $html .= '</li>';
                        break;
                    case 'divider':
                        $html .= '<li class="nav-item"><div style="background:#000000; height: 1px;"></div></li>';
                        break;
                    case 'link':
                    if ($uri == strtok($params['url'],'?')) {
                        $html .= '
                        <li class="nav-item dropdown-header"><i class="fas fa-folder-open"></i> '.$params['name'];
                        $html .= $this->addID($params['id']);
                        $html .= '
                        </li>';
                    } else {
                        $html .= '
                        <li class="nav-item sidemenu_item">
                            <a class="nav-link" href="'.$params['url'].'"><i class="fas fa-folder"></i> '.$params['name'];
                            $html .= $this->addID($params['id']);
                        $html .= '</a>
                        </li>';
                    }
                        break;
                }
            }
            $html .= '
                </ul>';
        }
        $html .= $this->sidem_footer;
        return $html;
    }
}