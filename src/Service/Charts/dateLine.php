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

        $chart .= $this->getExportMenu();

        return $chart;
	}

	private function newAxis($fulldate)
	{
        $tmp = 'chart_'.$this->graphName.'.scrollbarY = new am4core.Scrollbar();
        chart_'.$this->graphName.'.dateFormatter.inputDateFormat = "yyyy-MM-dd HH:mm"
        chart_'.$this->graphName.'.scrollbarY.parent = chart_'.$this->graphName.'.leftAxesContainer;
        chart_'.$this->graphName.'.scrollbarY.toBack();
        chart_'.$this->graphName.'.scrollbarY.exportable = false

        chart_'.$this->graphName.'.scrollbarX = new am4charts.XYChartScrollbar();
        chart_'.$this->graphName.'.scrollbarX.parent = chart_'.$this->graphName.'.bottomAxesContainer;
        chart_'.$this->graphName.'.scrollbarX.exportable = false

        var dateAxis_'.$this->graphName.' = chart_'.$this->graphName.'.xAxes.push(new am4charts.DateAxis());
        // dateAxis_'.$this->graphName.'.renderer.minGridDistance = 50
        // dateAxis_'.$this->graphName.'.dateFormats.setKey("month", "MMMM");
        // dateAxis_'.$this->graphName.'.periodChangeDateFormats.setKey("month", "MMMM");
        dateAxis_'.$this->graphName.'.baseInterval = { "timeUnit": "minute", "count": 1 } 
        dateAxis_'.$this->graphName.'.tooltipDateFormat = "HH:mm, d MMMM";
        dateAxis_'.$this->graphName.'.renderer.grid.template.strokeOpacity = 1;

        var valueAxis_'.$this->graphName.' = chart_'.$this->graphName.'.yAxes.push(new am4charts.ValueAxis());
        valueAxis_'.$this->graphName.'.renderer.grid.template.strokeOpacity = 1;

		chart_'.$this->graphName.'.cursor = new am4charts.XYCursor();
		chart_'.$this->graphName.'.cursor.behavior = "panXY";
		chart_'.$this->graphName.'.cursor.xAxis = dateAxis_'.$this->graphName.';
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

        function createSeriesDateLine(dateField, serieName) {
            let series = new am4charts.LineSeries();
            series.dataFields.valueY = "s_"+serieName;
            series.dataFields.dateX = dateField;
            series.tooltipText = "{dateX}:{valueY}";
            series.strokeWidth = 2;
			series.minBulletDistance = 15;
			series.name = serieName;

            series.tooltip.background.cornerRadius = 20;
            series.tooltip.background.strokeOpacity = 0;
            series.tooltip.pointerOrientation = "vertical";
            series.tooltip.label.minWidth = 40;
            series.tooltip.label.minHeight = 40;
            series.tooltip.label.textAlign = "middle";
            series.tooltip.label.textValign = "middle";

            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.strokeWidth = 2;
            bullet.circle.radius = 4;
            bullet.circle.fill = am4core.color("#fff");
            
            var bullethover = bullet.states.create("hover");
            bullethover.properties.scale = 1.3;

            return series
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
            var data = ev.target.data;
            console.log(data)
            if (ev.target.data.length == 0) {
                chart_'.$this->graphName.'.closeAllPopups()
                chart_'.$this->graphName.'.openPopup("Pas de données en base<br/>avec les filtres sélectionnés.")
            } else 
            for (var i = 0; i < data.length; i++) {
                for (var val in data[i]) {
                    if (val == "date") continue
                    serie_name = val.toString().split("_")
                    if (!(val in Series_'.$this->graphName.')) {
                        Series_'.$this->graphName.'[val] = createSeriesDateLine("date", serie_name[1])
                        chart_'.$this->graphName.'.series.push(Series_'.$this->graphName.'[val])
                        chart_'.$this->graphName.'.scrollbarX.series.push(Series_'.$this->graphName.'[val])
                    }
                }
            }
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
        $tmp .= $this->initSeries();
		$tmp .= $this->setVal();
        $tmp .= $this->newAxis($fulldate);
        $tmp .= $this->addThemeSwitcher();
		return $tmp;
    }
}