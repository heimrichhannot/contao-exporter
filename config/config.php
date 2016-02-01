<?php

/**
 * Constants
 */
define('EXPORTER_RAW_FIELD_SUFFIX', 'ERawE');

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