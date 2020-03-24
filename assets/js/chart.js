import * as am4core from '@amcharts/amcharts4/core';
import * as am4charts from '@amcharts/amcharts4/charts';
import am4lang_fr_FR from "@amcharts/amcharts4/lang/fr_FR";

import am4themes_kelly from '@amcharts/amcharts4/themes/kelly';
import am4themes_animated from '@amcharts/amcharts4/themes/animated';

global.am4core = am4core;
global.am4charts = am4charts;
global.am4lang_fr_FR = am4lang_fr_FR;
global.am4themes_kelly = am4themes_kelly;
global.am4themes_animated = am4themes_animated;

am4core.options.commercialLicense = true;
am4core.options.autoSetClassName = true;
console.log('amChart Core & themes (chart version) loaded');