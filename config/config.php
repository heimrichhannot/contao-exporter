<?php

/**
 * Constants
 */
define('EXPORTER_RAW_FIELD_SUFFIX', 'ERawE');
define('EXPORTER_FILE_TYPE_CSV', 'csv');
define('EXPORTER_FILE_TYPE_MEDIA', 'media');
define('EXPORTER_FILE_TYPE_PDF', 'pdf');
define('EXPORTER_FILE_TYPE_XLS', 'xls');
define('EXPORTER_COMPESSION_TYPE_ZIP', 'zip');
define('EXPORTER_TARGET_DOWNLOAD', 'download');
define('EXPORTER_FRONTEND_FORMID', 'exporter_download');


/**
 * Frontend modules
 */
array_insert(
	$GLOBALS['FE_MOD'], count($GLOBALS['FE_MOD']) - 1, array(
		'exporter' => array(
			'frontendExporter' => 'HeimrichHannot\Exporter\ModuleFrontendExporter'
		)
	)
);


/**
 * Back end modules
 */
array_insert(
	$GLOBALS['BE_MOD']['devtools'],
	1,
	array
	(
		'exporter' => array
		(
			'tables' => array('tl_exporter'),
			'icon'   => 'system/modules/exporter/assets/img/icon_export.png',
		)
	)
);

/**
 * Models
 */
$GLOBALS['TL_MODELS'][\HeimrichHannot\Exporter\ExporterModel::getTable()] = '\HeimrichHannot\Exporter\ExporterModel';