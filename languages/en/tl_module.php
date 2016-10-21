<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_module'];

/**
 * Fields
 */
$arrLang['exporterConfig'] = array('Exporter configuration', 'Choose the exporter config to be used.');
$arrLang['exporterBtnLabel'] = array('Button label', 'Type in a label for the button here.');
$arrLang['exporterExportType'] = array(
	0 => 'Export type',
	1 => 'Choose an export type here.',
	'list' => 'List export',
	'item' => 'Item export'
);
$arrLang['exporterUseIdFromUrl'] = array('Get item ID by GET parameter "id"', 'Activate this to retrieve the ID from the GET parameter "id". (e.g. admin exports user data');
$arrLang['exporterUseIdGroups'] = array('Groups restriction', 'Choose the groups having the right to export by using the GET parameter "id".');


/**
 * Legends
 */
$arrLang['exporter_legend'] = 'Exporter settings';