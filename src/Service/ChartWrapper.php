<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use \koolreport\processes\Group;
use \koolreport\processes\Sort;
use \koolreport\processes\Limit;
use \koolreport\processes\Custom;
use \koolreport\widgets\google\GeoChart;

use App\Service\QueryManager;
use App\Service\Charts\gauge;
use App\Service\Charts\groupedBar;
use App\Service\Charts\stackedBar;
use App\Service\Charts\stackedClusteredBar;
use App\Service\Charts\dateLine;
use App\Service\Charts\rangeSlider;
use App\Service\Charts\normalArray;
use App\Service\Charts\complexArray;

use App\Service\Charts\map;

class ChartWrapper
{
    private $amChartStarted = false;
    private $actualTheme;
    private $session;
    private $QM;

    public function __construct(SessionInterface $session, QueryManager $queryManager)
    {
        $this->session = $session;
        $this->QM = $queryManager;
    }

    public function getName($query, $params)
    {
        $data = $this->QM->getQueryLabel($query, $params);
        return $data;
    }

    private function initAmChart($theme)
    {
        if ($this->amChartStarted) return '';
        else {
            $this->amChartStarted = true;
            $this->actualTheme = $theme;
            return '
                am4core.useTheme(am4themes_animated);
                am4core.useTheme(am4themes_'.$theme.');
            ';
        }
    }

    private function exchangeTheme($newTheme)
    {
        if ($newTheme == $this->actualTheme) return '';
        else {
            $tmp = '
                am4core.unuseTheme(am4themes_'.$this->actualTheme.');
                am4core.useTheme(am4themes_'.$newTheme.');
            ';
            $this->actualTheme = $newTheme;
            return $tmp;
        }
    }

    public function createSimple(string $type, string $query, string $theme, string $title, string $css, bool $fulldate = true, bool $percent = false)
    {
        $start = $this->initAmChart($theme);
        $chartClass = 'App\\Service\\Charts\\'.$type;
        $chart = new $chartClass($query, $title);
        return $chart->createSimple($start, $css, $fulldate, $percent);
    }

    public function create(string $type, string $query, string $theme, string $title, string $css, bool $fulldate = true, bool $percent = false)
    {
        $start = $this->initAmChart($theme);
        $themeSwap = $this->exchangeTheme($theme);

        $chartClass = 'App\\Service\\Charts\\'.$type;
        $chart = new $chartClass($query, $title);
        return $chart->create($start.' '.$themeSwap, $css, $fulldate, $percent);
    }
}