<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package Exporter
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'HeimrichHannot\Exporter\ExporterModel' => 'system/modules/exporter/models/ExporterModel.php',

	// Classes
	'HeimrichHannot\Exporter\CsvExporter'   => 'system/modules/exporter/classes/CsvExporter.php',
	'HeimrichHannot\Exporter\Exporter'      => 'system/modules/exporter/classes/Exporter.php',
	'HeimrichHannot\Exporter\Helper'        => 'system/modules/exporter/classes/Helper.php',
	'HeimrichHannot\Exporter\XlsExporter'   => 'system/modules/exporter/classes/XlsExporter.php',
));
