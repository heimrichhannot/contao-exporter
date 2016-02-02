<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
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
	// Classes
	'HeimrichHannot\Exporter\XlsExporter'   => 'system/modules/exporter/classes/XlsExporter.php',
	'HeimrichHannot\Exporter\CsvExporter'   => 'system/modules/exporter/classes/CsvExporter.php',
	'HeimrichHannot\Exporter\Exporter'      => 'system/modules/exporter/classes/Exporter.php',
	'HeimrichHannot\Exporter\Helper'        => 'system/modules/exporter/classes/Helper.php',
	'HeimrichHannot\Exporter\MediaExporter' => 'system/modules/exporter/classes/MediaExporter.php',

	// Models
	'HeimrichHannot\Exporter\ExporterModel' => 'system/modules/exporter/models/ExporterModel.php',
));
