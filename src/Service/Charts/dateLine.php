<?php

namespace App\Service\Charts;

use App\Service\Charts\common;

class dateLine extends common
{
	private function newChart()
	{
		$chart = '
		// create chart
        var chart_'.$this->graphName.' = am4core.create("chart_'.$this->graphName.'", am4charts.XYChart);
        chart_'.$this->graphName.'.language.locale = am4lang_fr_FR;

        window.chart_'.$this->graphName.' = chart_'.$this->graphName.';

        // Separateur de decimales
        chart_'.$this->graphName.'.numberFormatter = new am4core.NumberFormatter();
        chart_'.$this->graphName.'.numberFormatter.numberFormat = "#,###";
        // chart_'.$this->graphName.'.language.locale["_decimalSeparator"] = ",";
        // chart_'.$this->graphName.'.language.locale["_thousandSeparator"] = " ";
        ';
        
        // if ($this->isPercent) {
        //     $chart .= '// Pourcentages
        //     chart_'.$this->graphName.'.numberFormatter.numberFormat = "#\'%\'"; 
        //     ';
        // }

        return $chart;
	}

	private function newAxis($fulldate)
	{
        if (!$fulldate)
            $tmp = '
        chart_'.$this->graphName.'.dateFormatter.inputDateFormat = "MM-dd"
        ';
        else $tmp = '';

        $tmp .= 'chart_'.$this->graphName.'.scrollbarY = new am4core.Scrollbar();
        chart_'.$this->graphName.'.scrollbarY.parent = chart_'.$this->graphName.'.leftAxesContainer;
        chart_'.$this->graphName.'.scrollbarY.toBack();
        chart_'.$this->graphName.'.scrollbarY.exportable = false

        chart_'.$this->graphName.'.scrollbarX = new am4charts.XYChartScrollbar();
        chart_'.$this->graphName.'.scrollbarX.parent = chart_'.$this->graphName.'.bottomAxesContainer;
        chart_'.$this->graphName.'.scrollbarX.exportable = false

        var dateAxis_'.$this->graphName.' = chart_'.$this->graphName.'.xAxes.push(new am4charts.CategoryAxis());
        dateAxis_'.$this->graphName.'.dataFields.category = "category";
        dateAxis_'.$this->graphName.'.renderer.minGridDistance = 50
        // dateAxis_'.$this->graphName.'.dateFormats.setKey("month", "MMMM");
        // dateAxis_'.$this->graphName.'.periodChangeDateFormats.setKey("month", "MMMM");
        dateAxis_'.$this->graphName.'.renderer.grid.template.strokeOpacity = 1;

        var valueAxis_'.$this->graphName.' = chart_'.$this->graphName.'.yAxes.push(new am4charts.ValueAxis());
        valueAxis_'.$this->graphName.'.renderer.grid.template.strokeOpacity = 1;

		// chart_'.$this->graphName.'.cursor = new am4charts.XYCursor();
		// chart_'.$this->graphName.'.cursor.behavior = "panXY";
		// chart_'.$this->graphName.'.cursor.xAxis = dateAxis_'.$this->graphName.';
        ';
        if ($this->isPercent) $tmp .= '
        valueAxis_'.$this->graphName.'.max = 100
        valueAxis_'.$this->graphName.'.renderer.labels.template.adapter.add("text", function(text) {
            return text + "%";
        });
        ';
        return $tmp;
    }
    
    private function addThemeSwitcher()
    {
        return '
        chart_'.$this->graphName.'.dark = function() {
            this.scrollbarX.background.fill = am4core.color("#2b2b2b");
            this.scrollbarX.background.fillOpacity = 0.4

            this.scrollbarX.unselectedOverlay.fill = am4core.color("#000000");
            this.scrollbarX.unselectedOverlay.fillOpacity = 0.8
            this.scrollbarX.unselectedOverlay.stroke = am4core.color("#2c2c2c");

            this.scrollbarX.thumb.background.fill = am4core.color("#000000");
            this.scrollbarX.thumb.background.stroke = am4core.color("#2f2f2f");
            this.scrollbarX.thumb.background.fillOpacity = 0.5
            this.scrollbarX.thumb.background.strokeOpacity = 1

            this.scrollbarX.startGrip.background.fill = am4core.color("#3b3b3b");
            this.scrollbarX.startGrip.background.stroke = am4core.color("#2c2c2c");
            this.scrollbarX.startGrip.icon.stroke = am4core.color("#000000");

            this.scrollbarX.endGrip.background.fill = am4core.color("#3b3b3b");
            this.scrollbarX.endGrip.background.stroke = am4core.color("#2c2c2c");
            this.scrollbarX.endGrip.icon.stroke = am4core.color("#000000");

            this.scrollbarY.background.fill = am4core.color("#2b2b2b");
            this.scrollbarY.background.fillOpacity = 0.4

            this.scrollbarY.startGrip.background.fill = am4core.color("#3b3b3b");
            this.scrollbarY.startGrip.background.stroke = am4core.color("#2c2c2c");
            this.scrollbarY.startGrip.icon.stroke = am4core.color("#000000");

            this.scrollbarY.endGrip.background.fill = am4core.color("#3b3b3b");
            this.scrollbarY.endGrip.background.stroke = am4core.color("#2c2c2c");
            this.scrollbarY.endGrip.icon.stroke = am4core.color("#000000");

            this.scrollbarY.thumb.background.fill = am4core.color("#3b3b3b");
            this.scrollbarY.thumb.background.stroke = am4core.color("#2f2f2f");
            this.scrollbarY.thumb.background.fillOpacity = 0.5
            this.scrollbarY.thumb.background.strokeOpacity = 1

            valueAxis_'.$this->graphName.'.renderer.grid.template.stroke = am4core.color("#474c51");
            dateAxis_'.$this->graphName.'.renderer.grid.template.stroke = am4core.color("#474c51");

            valueAxis_'.$this->graphName.'.renderer.labels.template.fill = am4core.color("#ffffff");
            dateAxis_'.$this->graphName.'.renderer.labels.template.fill = am4core.color("#ffffff");

            chart_'.$this->graphName.'.legend.labels.template.fill = am4core.color("#ffffff");
        }

        chart_'.$this->graphName.'.light = function() {
            this.scrollbarX.background.fill = am4core.color("#f3f3f3");
            this.scrollbarX.background.fillOpacity = 0.5

            this.scrollbarX.unselectedOverlay.fill = am4core.color("#ffffff");
            this.scrollbarX.unselectedOverlay.fillOpacity = 0.8
            this.scrollbarX.unselectedOverlay.stroke = am4core.color("#ffffff");

            this.scrollbarX.thumb.background.fill = am4core.color("#ffffff");
            this.scrollbarX.thumb.background.stroke = am4core.color("#ffffff");
            this.scrollbarX.thumb.background.fillOpacity = 0
            this.scrollbarX.thumb.background.strokeOpacity = 1

            this.scrollbarX.startGrip.background.fill = am4core.color("#d9d9d9");
            this.scrollbarX.startGrip.background.stroke = am4core.color("#ffffff");
            this.scrollbarX.startGrip.icon.stroke = am4core.color("#ffffff");

            this.scrollbarX.endGrip.background.fill = am4core.color("#d9d9d9");
            this.scrollbarX.endGrip.background.stroke = am4core.color("#ffffff");
            this.scrollbarX.endGrip.icon.stroke = am4core.color("#ffffff");

            this.scrollbarY.background.fill = am4core.color("#f3f3f3");
            this.scrollbarY.background.fillOpacity = 0.5

            this.scrollbarY.startGrip.background.fill = am4core.color("#d9d9d9");
            this.scrollbarY.startGrip.background.stroke = am4core.color("#ffffff");
            this.scrollbarY.startGrip.icon.stroke = am4core.color("#ffffff");

            this.scrollbarY.endGrip.background.fill = am4core.color("#d9d9d9");
            this.scrollbarY.endGrip.background.stroke = am4core.color("#ffffff");
            this.scrollbarY.endGrip.icon.stroke = am4core.color("#ffffff");

            this.scrollbarY.thumb.background.fill = am4core.color("#d9d9d9");
            this.scrollbarY.thumb.background.stroke = am4core.color("#ffffff");
            this.scrollbarY.thumb.background.fillOpacity = 1
            this.scrollbarY.thumb.background.strokeOpacity = 1

            valueAxis_'.$this->graphName.'.renderer.grid.template.stroke = am4core.color("#a5a5a5");
            dateAxis_'.$this->graphName.'.renderer.grid.template.stroke = am4core.color("#a5a5a5");

            valueAxis_'.$this->graphName.'.renderer.labels.template.fill = am4core.color("#000000");
            dateAxis_'.$this->graphName.'.renderer.labels.template.fill = am4core.color("#000000");

            chart_'.$this->graphName.'.legend.labels.template.fill = am4core.color("#000000");
        }
        ';
    }

    private function initSeries()
    {
        return '
        var Series_'.$this->graphName.' = {};

        function createSeriesDateLine(name) {
            let new_series = new am4charts.LineSeries();
            new_series.dataFields.valueY = name;
            new_series.dataFields.categoryX = "category";
            new_series.strokeWidth = 2;
			// new_series.minBulletDistance = 15;
			new_series.name = name;

            let bullet = new_series.bullets.push(new am4charts.CircleBullet());
            // bullet.circle.strokeWidth = 2;
            // bullet.circle.radius = 4;
            // bullet.circle.fill = am4core.color("#fff");

            // new_series.tooltip.background.cornerRadius = 20;
            // new_series.tooltip.background.strokeOpacity = 0;
            // new_series.tooltip.pointerOrientation = "vertical";
            // new_series.tooltip.label.minWidth = 40;
            // new_series.tooltip.label.minHeight = 40;
            // new_series.tooltip.label.textAlign = "middle";
            // new_series.tooltip.label.textValign = "middle";
            new_series.tooltipText = "{valueY}";

            let bullethover = bullet.states.create("hover");
            bullethover.properties.scale = 1.3;

            return new_series
        }';
    }

	private function setVal()
	{
        return '
        chart_'.$this->graphName.'.dataSource.url = document.location.protocol+"//"+document.location.hostname+"/chart/dateLine/'.$this->query.'/data.json"
        chart_'.$this->graphName.'.dataSource.disableCache = true
        chart_'.$this->graphName.'.dataSource.parser = new am4core.JSONParser();
        chart_'.$this->graphName.'.legend = new am4charts.Legend();
        chart_'.$this->graphName.'.dataSource.events.on("parseended", function(ev) {
            var series_list = ev.target.data.series
            var data = ev.target.data.values;

            if (data.length == 0) {
                chart_'.$this->graphName.'.closeAllPopups()
                chart_'.$this->graphName.'.openPopup("Pas de données en base<br/>avec les filtres sélectionnés.")
            } else 

            ev.target.data = data
            console.log(ev.target.data)
            // for (var i = 0; i < data.length; i++) {
            //     for (var val in data[i]) {
            //         serie_name = val.toString().split("_")
            //         if (!(val in Series_'.$this->graphName.')) {
            //             Series_'.$this->graphName.'[val] = createSeriesDateLine("date", serie_name[1])
            //             chart_'.$this->graphName.'.series.push(Series_'.$this->graphName.'[val])
            //             chart_'.$this->graphName.'.scrollbarX.series.push(Series_'.$this->graphName.'[val])
            //         }
            //     }
            // }
            series_list.forEach(function(name) {
                serie_name = name.toString().split("_")
                Series_'.$this->graphName.'[name] = createSeriesDateLine(name)
                chart_'.$this->graphName.'.series.push(Series_'.$this->graphName.'[name])
                chart_'.$this->graphName.'.scrollbarX.series.push(Series_'.$this->graphName.'[name])
            })
        });

        chart_'.$this->graphName.'.UpdateChart = function() {
            chart_'.$this->graphName.'.closeAllPopups()

            for (var val in Series_'.$this->graphName.') {
                let index = chart_'.$this->graphName.'.series.indexOf(Series_'.$this->graphName.'[val])
                chart_'.$this->graphName.'.scrollbarX.scrollbarChart.series.removeIndex(index).dispose();
                chart_'.$this->graphName.'.series.removeIndex(index).dispose();
                delete Series_'.$this->graphName.'[val]
            }
            chart_'.$this->graphName.'.dataSource.load()
        }
        ';
	}

    public function getScript($theme, $css, $fulldate = false, $percent = false)
    {
		$tmp = $theme;
		$tmp .= $this->newChart();
        $tmp .= $this->newAxis($fulldate);
        $tmp .= $this->initSeries();
		$tmp .= $this->setVal();
        $tmp .= $this->addThemeSwitcher();

		return $tmp;
    }
}