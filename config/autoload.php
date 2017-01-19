<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(
    [
	'HeimrichHannot',]
);


/**
 * Register the classes
 */
ClassLoader::addClasses(
    [
	// Modules
	'HeimrichHannot\Exporter\ModuleFrontendExporter' => 'system/modules/exporter/modules/ModuleFrontendExporter.php',
	'HeimrichHannot\Exporter\ModuleExporter'         => 'system/modules/exporter/modules/ModuleExporter.php',

	// Classes
	'HeimrichHannot\Exporter\Backend'                => 'system/modules/exporter/classes/Backend.php',
	'HeimrichHannot\Exporter\Concrete\XlsExporter'   => 'system/modules/exporter/classes/concrete/XlsExporter.php',
	'HeimrichHannot\Exporter\Concrete\CsvExporter'   => 'system/modules/exporter/classes/concrete/CsvExporter.php',
	'HeimrichHannot\Exporter\Concrete\MediaExporter' => 'system/modules/exporter/classes/concrete/MediaExporter.php',
	'HeimrichHannot\Exporter\Concrete\PdfExporter'   => 'system/modules/exporter/classes/concrete/PdfExporter.php',
	'HeimrichHannot\Exporter\PhpExcelExporter'       => 'system/modules/exporter/classes/PhpExcelExporter.php',
	'HeimrichHannot\Exporter\Exporter'               => 'system/modules/exporter/classes/Exporter.php',
	'HeimrichHannot\Exporter\Helper'                 => 'system/modules/exporter/classes/Helper.php',

	// Models
	'HeimrichHannot\Exporter\ExporterModel'          => 'system/modules/exporter/models/ExporterModel.php',]
);


/**
 * Register the templates
 */
TemplateLoader::addFiles(
    [
	'mod_frontend_export'       => 'system/modules/exporter/templates/modules',
	'exporter_pdf_item_default' => 'system/modules/exporter/templates',]
);
