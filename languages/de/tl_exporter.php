<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_exporter'];

/**
 * Fields
 */
$arrLang['title'] = array('Titel', 'Geben Sie hier den Titel für die Export-Konfiguration ein.');
$arrLang['type'] = array('Typ', 'Wählen Sie hier, welcher Typ verwendet werden soll.');

// table legend
$arrLang['globalOperationKey'] = array('Globale Operation', 'Wählen Sie hier die Operation aus, die den Export auslösen soll.');
$arrLang['linkedTable'] = array('Verknüpfte Tabelle', 'Wählen Sie hier die Tabelle aus, die exportiert werden soll.');
$arrLang['tableFieldsForExport'] = array('Felder', 'Wählen Sie hier die Felder aus, die exportiert werden sollen.');
$arrLang['localizeFields'] = array('Feldwerte lokalisieren', 'Wählen Sie diese Option, wenn die Feldwerte lokalisiert werden sollen.');
$arrLang['addJoinTables'] = array('Join hinzufügen', 'Wählen Sie diese Option, wenn die verknüpfte Tabelle mit einer oder mehreren anderen Tabellen vereint werden soll.');
$arrLang['joinTables'] = array('Join-Elemente', '');
$arrLang['joinTable'] = array('Tabelle', 'Wählen Sie die Tabelle aus, die mit der verknüpften Tabelle vereint werden soll. ');
$arrLang['joinCondition'] = array('ON-Bedingung', 'Geben Sie hier Bedingungen für die ON-Klausel in der Form "Verknüpfte-Tabelle.Wert = Join-Tabelle.Wert" ein.');
$arrLang['addUnformattedFields'] = array('Unformatierte Felder nutzen', 'Wählen Sie diese Option, wenn Felder in unformatierter Form exportierbar sein sollen.');
$arrLang['whereClause'] = array('WHERE-Bedingung', 'Geben Sie hier eine WHERE-Bedingung in der Form column=X an. Bei Join-Abfragen muss die Eingabe in der Form table.column=X erweitert werden. Zeit-Bedingungen müssen als timestamp angegeben werden.');
$arrLang['orderBy'] = array('ORDER BY-Bedingung', 'Geben Sie hier eine Bedingung an, nach der der Export sortiert werden soll (zB tstamp ASC).');
$arrLang['skipFields'] = array('Felder überspringen', 'Wählen Sie hier die Felder aus, die nicht in der Feldliste enthalten sein sollen.');
$arrLang['skipLabels'] = array('Labels überspringen', 'Wählen Sie hier die Felder aus, deren Label nicht in der Feldliste enthalten sein sollen.');

// export legend
$arrLang['fileType'] = array('Dateiformat', 'Wählen Sie hier das Dateiformat, in das exportiert werden soll.');
$arrLang['fileType'][EXPORTER_FILE_TYPE_CSV] = 'CSV (kommaseparierte Werte)';
$arrLang['fileType'][EXPORTER_FILE_TYPE_MEDIA] = 'Verknüpfte Dateien als Archiv';
$arrLang['fileType'][EXPORTER_FILE_TYPE_PDF] = 'PDF';
$arrLang['fileType'][EXPORTER_FILE_TYPE_XLS] = 'XLS (Microsoft Excel)';
$arrLang['exporterClass'] = array('Exporterklasse', 'Wählen Sie hier die PHP-Klasse, die als Exporter fungieren soll. Die Klasse muss eine konkrete Klasse im Namespace "HeimrichHannot\\Exporter\\Concrete" sein.');
$arrLang['target'] = array('Ziel', 'Wählen Sie hier das Dateiformat, in das exportiert werden soll.');
$arrLang['fileDir'] = array('Verzeichnis', 'Wählen Sie hier das Verzeichnis aus, in das exportiert werden soll. Komplexere Exportpfade können mit einem Hook gesetzt werden (siehe README.md).');
$arrLang['useHomeDir'] = array('Benutzerverzeichnisse verwenden', 'Wählen Sie diese Option, wenn die exportierten Dateien vorrangig dem Benutzerverzeichnis hinzugefügt werden sollen. Hat das aktuell eingeloggte Mitglied kein Benutzerverzeichnis, wird das Verzeichnis im vorigen Feld genutzt.');
$arrLang['useProtectedHomeDir'] = array('Geschützte Benutzerverzeichnisse verwenden', 'Wählen Sie diese Option, wenn die exportierten Dateien vorrangig dem geschützten Benutzerverzeichnis, dann dem normalen und dann dem Exportverzeichnis hinzugefügt werden sollen.');
$arrLang['fileSubDirName'] = array('Unterverzeichnisname', 'Geben Sie hier den Namen des Unterverzeichnisses an, der zum Exportpfad hinzugefügt werden soll. Komplexere Exportpfade können mit einem Hook gesetzt werden (siehe README.md).');
$arrLang['fileName'] = array('Dateinamen überschreiben (Standard: "export")', 'Geben Sie hier den Namen der zu exportierenden Datei an. Komplexere Exportpfade können mit einem Hook gesetzt werden (siehe README.md).');
$arrLang['fileNameAddDatime'] = array('Datum & Uhrzeit dem Dateinamen voranstellen', 'Wählen Sie diese Option, wenn dem Dateinamen Datum & Uhrzeit vorangestellt werden soll.');
$arrLang['fileNameAddDatimeFormat'] = array('Datumsformat überschreiben', 'Geben Sie hier ein abweichendes Datumsformat für den Dateinamen ein (es werden die date()-Variablen von PHP unterstützt).');
$arrLang['fieldDelimiter'] = array('Feld-Trennzeichen', 'Geben Sie hier das Feld-Trennzeichen ein.');
$arrLang['fieldEnclosure'] = array('Text-Trennzeichen', 'Geben Sie hier das Text-Trennzeichen ein.');
$arrLang['compressionType'] = array('Kompressionsformat', 'Wählen Sie hier aus, in welchem Format die exportierten Binärdateien zusammengefasst werden sollen.');
$arrLang['compressionType']['zip'] = 'ZIP';
$arrLang['pdfBackground'] = array('Hintergrund', 'Wählen Sie hier ein PDF-Template als grafisches Grundgerüst.');
$arrLang['pdfTemplate'] = array('Template', 'Wählen Sie hier ein Template für die PDF.');
$arrLang['pdfCss'] = array('CSS-Styles', 'Wählen Sie hier bei Bedarf CSS-Dateien aus, die auf den Inhalt des PDFs angewendet werden. Unterstützte CSS-Regeln siehe <a href="https://mpdf.github.io">https://mpdf.github.io</a>.');
$arrLang['pdfMargins'] = array('Seitenränder', 'Wählen Sie hier die Seitenabstände, die im PDF verwendet werden sollen.');
$arrLang['pdfTitle'] = array('Meta-Titel', 'Hier können Sie den Titel für das Dokument angeben.');
$arrLang['pdfSubject'] = array('Meta-Thema', 'Hier können Sie das Thema für das Dokument angeben.');
$arrLang['pdfCreator'] = array('Meta-Autor', 'Hier können Sie den Autoren für das Dokument angeben.');
$arrLang['pdfFonts'] = array('Schriften', 'Wählen Sie hier die Schriften aus, die im PDF verwendet werden sollen.');
$arrLang['exporter_pdfFonts_fontName'] = array('Schriftname', 'Geben Sie hier den Namen der Schrift ein, der im CSS verwendet werden soll. WICHTIG: Bitte beachten Sie, dass jedes Zeichen in Kleinbuchstaben konvertiert word und Leerzeichen entfernt werden!');
$arrLang['exporter_pdfFonts_fontWeight'] = array('Gewicht', 'Wählen Sie hier das Gewicht der Schrift aus. Im CSS sprechen Sie die Gewichte wie gewohnt mit bspw. "font-weight: bold" an.');
$arrLang['exporter_pdfFonts_file'] = array('Schriftdatei', 'Wählen Sie hier die TrueType-Schrift (*.ttf) aus, die dem Gewicht entspricht.');


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

/**
 * References
 */
$arrLang['reference'][\HeimrichHannot\Exporter\Exporter::TYPE_LIST] = 'Listenexport';
$arrLang['reference'][\HeimrichHannot\Exporter\Exporter::TYPE_ITEM] = 'Einzelexport';
$arrLang['reference'][\HeimrichHannot\Exporter\Exporter::TARGET_DOWNLOAD] = 'Download';
$arrLang['reference'][\HeimrichHannot\Exporter\Exporter::TARGET_FILE] = 'Datei';
$arrLang['reference']['fontWeights'] = array(
    'R' => 'Normal',
    'B' => 'Fett',
    'I' => 'Kursiv',
    'BI' => 'Fett und Kursiv'
);