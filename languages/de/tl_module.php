<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_module'];

/**
 * Fields
 */
$arrLang['exporterConfig'] = array('Exporterkonfiguration', 'Wählen Sie hier die Exporterkonfiguration, welche ausgelöst werden soll.');
$arrLang['exporterBtnLabel'] = array('Buttonbeschriftung', 'Geben Sie hier die Beschriftung des Buttons ein.');
$arrLang['exporterExportType'] = array(
	0 => 'Export-Typ',
	1 => 'Wählen Sie hier, welcher Export-Typ verwendet werden soll.',
	'list' => 'Listenexport',
	'item' => 'Einzelexport'
);
$arrLang['exporterUseIdFromUrl'] = array('Item-ID aus URL-Parameter "id" beziehen', 'Aktivieren, um den Datensatz der ID aus dem URL-Parameter zu exportieren. (z. B. Admin exportiert Nutzerdaten');
$arrLang['exporterUseIdGroups'] = array('Gruppeneinschränkung', 'Wählen Sie hier die Gruppen aus, die das Recht haben, Daten anhand des URL-Parameters zu exportieren.');


/**
 * Legends
 */
$arrLang['exporter_legend'] = 'Exportereinstellungen';