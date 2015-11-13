<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_exporter'];

/**
 * Fields
 */
$arrLang['title'] = array('Titel', 'Geben Sie hier den Titel für die Export-Konfiguration ein.');
// table legend
$arrLang['globalOperationKey'] = array('Globale Operation', 'Wählen Sie hier die Operation aus, die den Export auslösen soll.');
$arrLang['linkedTable'] = array('Verknüpfte Tabelle', 'Wählen Sie hier die Tabelle aus, welche exportiert werden soll.');
$arrLang['tableFieldsForExport'] = array('Felder', 'Wählen Sie hier die Felder aus, welche exportiert werden sollen.');
// export legend
$arrLang['fileType'] = array('Dateiformat', 'Wählen Sie hier das Dateiformat, in welches exportiert werden soll.');
$arrLang['fieldDelimiter'] = array('Feld-Trennzeichen', 'Geben Sie hier das Feld-Trennzeichen ein.');
$arrLang['fieldEnclosure'] = array('Text-Trennzeichen', 'Geben Sie hier das Text-Trennzeichen ein.');
$arrLang['addHeaderToExportTable'] = array('Feldnamen im Tabellenkopf anzeigen', 'Wählen Sie hier, ob im Tabellenkopf die Feldnamen eingetragen werden sollen.');
// localization
$arrLang['localizeHeader'] = array('Tabellenkopf lokalisieren', 'Wählen Sie hier, ob die Feldnamen lokalisiert werden sollen. Hat nur Auswirkungen, wenn Tabellenkopf angezeigt wird.');
$arrLang['localizeFields'] = array('Tabellenwert lokalisieren', 'Wählen Sie hier, ob die Feldwerte lokalisiert werden sollen.');


/**
 * Legends
 */
$arrLang['title_legend'] = 'Allgemeines';
$arrLang['table_legend'] = 'Konfiguration - Operation und Tabelle';
$arrLang['export_legend'] = 'Konfiguration - Export';
$arrLang['localization_legend'] = 'Konfiguration - Lokalisierung';


/**
 * Buttons
 */
$arrLang['new'] = array('Neue Export-Konfiguration', 'Eine neue Export-Konfiguration erstellen');
$arrLang['show'] = array('Export-Konfiguration Details', 'Die Details der Export-Konfiguration ID %s anzeigen');
$arrLang['edit'] = array('Export-Konfiguration bearbeiten', 'Export-Konfiguration ID %s bearbeiten');
$arrLang['copy'] = array('Export-Konfiguration duplizieren', 'Export-Konfiguration ID %s duplizieren');
$arrLang['delete'] = array('Export-Konfiguration löschen', 'Export-Konfiguration ID %s löschen');