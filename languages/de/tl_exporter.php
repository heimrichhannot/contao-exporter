<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_exporter'];

/**
 * Fields
 */
$arrLang['title'] = array('Titel', 'Geben Sie hier den Titel für die Export-Konfiguration ein.');

// table legend
$arrLang['globalOperationKey'] = array('Globale Operation', 'Wählen Sie hier die Operation aus, die den Export auslösen soll.');
$arrLang['linkedTable'] = array('Verknüpfte Tabelle', 'Wählen Sie hier die Tabelle aus, die exportiert werden soll.');
$arrLang['tableFieldsForExport'] = array('Felder', 'Wählen Sie hier die Felder aus, die exportiert werden sollen.');
$arrLang['localizeFields'] = array('Feldwerte lokalisieren', 'Wählen Sie diese Option, wenn die Feldwerte lokalisiert werden sollen.');

// export legend
$arrLang['fileType'] = array('Dateiformat', 'Wählen Sie hier das Dateiformat, in das exportiert werden soll.');
$arrLang['fileType'][EXPORTER_FILE_TYPE_CSV] = 'CSV (kommaseparierte Werte)';
$arrLang['fileType'][EXPORTER_FILE_TYPE_XLS] = 'XLS (Microsoft Excel)';
$arrLang['fileType'][EXPORTER_FILE_TYPE_MEDIA] = 'Verknüpfte Dateien als Archiv';
$arrLang['fieldDelimiter'] = array('Feld-Trennzeichen', 'Geben Sie hier das Feld-Trennzeichen ein.');
$arrLang['fieldEnclosure'] = array('Text-Trennzeichen', 'Geben Sie hier das Text-Trennzeichen ein.');
$arrLang['compressionType'] = array('Kompressionsformat', 'Wählen Sie hier aus, in welchem Format die exportierten Binärdateien zusammengefasst werden sollen.');
$arrLang['compressionType']['zip'] = 'ZIP';

// header
$arrLang['addHeaderToExportTable'] = array('Feldnamen im Tabellenkopf anzeigen', 'Wählen Sie diese Option, wenn der Tabelle ein Tabellenkopf hinzugefügt werden soll.');
$arrLang['localizeHeader'] = array('Tabellenkopf lokalisieren', 'Wählen Sie diese Option, wenn die Feldnamen im Tabellenkopf lokalisiert werden sollen.');
$arrLang['overrideHeaderFieldLabels'] = array('Felder im Tabellenkopf überschreiben', 'Wählen Sie diese Option, wenn Sie die Namen von Feldern im Tabellenkopf anpassen möchten.');
$arrLang['headerFieldLabels'] = array('Tabellenkopffelder', 'Geben Sie her die gewünschten Änderungen ein.');
$arrLang['headerFieldLabels']['field'] = 'Feld';
$arrLang['headerFieldLabels']['label'] = 'Name';


/**
 * Legends
 */
$arrLang['title_legend'] = 'Allgemeines';
$arrLang['export_legend'] = 'Exporteinstellungen';
$arrLang['table_legend'] = 'Operation, Tabelle & Felder';


/**
 * Buttons
 */
$arrLang['new'] = array('Neue Export-Konfiguration', 'Eine neue Export-Konfiguration erstellen');
$arrLang['show'] = array('Export-Konfiguration Details', 'Die Details der Export-Konfiguration ID %s anzeigen');
$arrLang['edit'] = array('Export-Konfiguration bearbeiten', 'Export-Konfiguration ID %s bearbeiten');
$arrLang['copy'] = array('Export-Konfiguration duplizieren', 'Export-Konfiguration ID %s duplizieren');
$arrLang['delete'] = array('Export-Konfiguration löschen', 'Export-Konfiguration ID %s löschen');