<?php

namespace App\Service\Charts;

class common
{
    protected $query;
    protected $title;
    protected $graphName;
    protected $isPercent = false;
    protected $exportMenu = true;

    protected $mois = array(
        'janvier',
        'fevrier',
        'mars',
        'avril',
        'mai',
        'juin',
        'juilliet',
        'aout',
        'septembre',
        'octobre',
        'novembre',
        'decembre'
    );

    public function __construct($query, $title)
    {
		$this->title = $title;
		$this->query = $query;
		$this->graphName = uniqid();
    }

    public function getExportMethod()
    {
        $tmp = '
        chart_'.$this->graphName.'.exporting.adapter.add("formatDataFields", function(data, target) {
            data.dataFields = {};
            chart_'.$this->graphName.'.series.each(function(series) {
                data.dataFields[series.dataFields.dateX] = series.dataFields.dateX;
                if (series.visible) {
                    data.dataFields[series.dataFields.valueY] = series.dataFields.valueY;
                }
            });
            return data;
        });
        ';
        return $tmp;
    }

    public function getExportMenu(bool $withImage = true): string
    {
        if (!$this->exportMenu) return '';

		$menu = '
        chart_'.$this->graphName.'.exporting.menu = new am4core.ExportMenu();
        chart_'.$this->graphName.'.exporting.menu.align = "right";
        chart_'.$this->graphName.'.exporting.menu.verticalAlign = "bottom";
        chart_'.$this->graphName.'.exporting.filePrefix = "nVisio";
        chart_'.$this->graphName.'.exporting.title = "'.$this->title.'";
        // chart_'.$this->graphName.'.exporting.backgroundColor = am4core.color("#f00", 0);

        '.$this->getExportMethod().'

        chart_'.$this->graphName.'.exporting.menu.items = [
            {
            "label": "...",
            "menu": [
                {
                    "label": "Data",
                    "menu": [
                        { "type": "csv", "label": "CSV" },
                        { "type": "xlsx", "label": "XLSX" },
                    ]
                },
        ';
        if ($withImage) $menu .= '
                {
                    "label": "Image",
                    "menu": [
                        { "type": "png", "label": "PNG" },
                        { "type": "jpg", "label": "JPG" },
                        { "type": "svg", "label": "SVG" },
                        { "type": "pdf", "label": "PDF" }
                    ]
                },
                {
                    "label": "Imprimer", "type": "print"
                }
        ';
        $menu .= '
                ]
            }
        ];
        ';
        return $menu;
    }

    public function createSimple($theme, $css, $fulldate = false, $percent = false)
    {
        $this->isPercent = $percent;
        $this->exportMenu = false;

        $tmp = '
        <div id="chart_'.$this->graphName.'" query="'.$this->query.'" class="chartdiv bbChart '.$css.'"></div>
        ';
        $tmp .= '
        <script>
		am4core.ready(function() {';
        $tmp .= $this->getScript($theme, $css, $fulldate, $percent);
        $tmp .= '
        chartThemeSwitcher("chart_'.$this->graphName.'");
        }); // end am4core.ready()

        $( document ).ready(function() {
            chart_'.$this->graphName.'.light();
        });
        </script>';
		return $tmp;
    }

	public function create($theme, $css, $fulldate = false, $percent = false)
	{
        $this->isPercent = $percent;
        $tmp = '
        <div class="card chart-card">
            <div id="chart_'.$this->graphName.'_name" class="card-header">'.$this->title.'</div>
            <div class="card-body">
                <div id="chart_'.$this->graphName.'" query="'.$this->query.'" class="chartdiv bbChart '.$css.'"></div>
            </div>
        </div>';
        $tmp .= '
        <script>
		am4core.ready(function() {';
        $tmp .= $this->getScript($theme, $css, $fulldate, $percent);
        $tmp .= '
        chartThemeSwitcher("chart_'.$this->graphName.'")

        }); // end am4core.ready()
        </script>';
		return $tmp;
	}
}