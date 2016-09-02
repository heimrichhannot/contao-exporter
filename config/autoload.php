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
	// Modules
	'HeimrichHannot\Exporter\ModuleFrontendExporter' => 'system/modules/exporter/modules/ModuleFrontendExporter.php',
	'HeimrichHannot\Exporter\ModuleExporter'         => 'system/modules/exporter/modules/ModuleExporter.php',

	// Models
	'HeimrichHannot\Exporter\ExporterModel'          => 'system/modules/exporter/models/ExporterModel.php',

	// Classes
	'HeimrichHannot\Exporter\CsvExporter'            => 'system/modules/exporter/classes/CsvExporter.php',
	'HeimrichHannot\Exporter\PdfExporter'            => 'system/modules/exporter/classes/PdfExporter.php',
	'HeimrichHannot\Exporter\Exporter'               => 'system/modules/exporter/classes/Exporter.php',
	'HeimrichHannot\Exporter\Helper'                 => 'system/modules/exporter/classes/Helper.php',
	'HeimrichHannot\Exporter\MediaExporter'          => 'system/modules/exporter/classes/MediaExporter.php',
	'HeimrichHannot\Exporter\XlsExporter'            => 'system/modules/exporter/classes/XlsExporter.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_frontend_export'       => 'system/modules/exporter/templates/modules',
	'exporter_pdf_default_item' => 'system/modules/exporter/templates',
));
