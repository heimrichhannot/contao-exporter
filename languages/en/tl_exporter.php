<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_exporter'];

/**
 * Fields
 */
$arrLang['title'] = array('Title', 'Please type in the title for the exporter config.');

// table legend
$arrLang['globalOperationKey'] = array('Global operation', 'Choose the operation the exporter should invoke.');
$arrLang['linkedTable'] = array('Linked table', 'Choose the table, that should be exported.');
$arrLang['tableFieldsForExport'] = array('Fields', 'Choose the fields to be exported.');
$arrLang['localizeFields'] = array('Localize field values', 'Choose this option if field values should be localized.');
$arrLang['addJoinTables'] = array('Add join', 'Choose this option if the linked table should be joined with some other table.');
$arrLang['joinTables'] = array('Join elements', '');
$arrLang['joinTable'] = array('Join table', 'Choose the table to be joined with the linked table.');
$arrLang['joinCondition'] = array('ON condition', 'Please type in conditions for the ON clause in the form "linked_table.field = jin_table.field".');
$arrLang['addUnformattedFields'] = array('Use unformatted fields', 'Choose this option if you want to have unformatted fields in your export.');
$arrLang['whereClause'] = array('WHERE condition', 'Please type in a WHERE condition in the form column=X. In case of join the input needs to be extended in the form table.column=X. Temporal conditions need to be defined as timestamps.');
$arrLang['orderBy'] = array('ORDER BY condition', 'Please type in a condition for ordering the exported data (e.g. tstamp ASC).');

// export legend
$arrLang['fileType'] = array('File type', 'Choose the file type to be used for exporting.');
$arrLang['fileType'][EXPORTER_FILE_TYPE_CSV] = 'CSV (comma separated values)';
$arrLang['fileType'][EXPORTER_FILE_TYPE_MEDIA] = 'Linked files as archive';
$arrLang['fileType'][EXPORTER_FILE_TYPE_PDF] = 'PDF';
$arrLang['fileType'][EXPORTER_FILE_TYPE_XLS] = 'XLS (Microsoft Excel)';
$arrLang['target'] = array('Target', 'Choose the file type to be exported to.');
$arrLang['target'][EXPORTER_TARGET_DOWNLOAD] = 'Download';
$arrLang['fieldDelimiter'] = array('Field separator', 'Please type in the character separating fields.');
$arrLang['fieldEnclosure'] = array('Text separator', 'Please type in the character enclosing texts containing the field separator character.');
$arrLang['compressionType'] = array('Compression', 'Choose the format to use for compression of the archive.');
$arrLang['compressionType']['zip'] = 'ZIP';
$arrLang['pdfBackground'] = array('Background', 'Choose a pdf template as a graphical base.');
$arrLang['pdfTemplate'] = array('Template', 'Choose ein Template for the pdf.');


// header
$arrLang['addHeaderToExportTable'] = array('Export field names in table header', 'Choose this option if you want to have the field names in the table\'s header.');
$arrLang['localizeHeader'] = array('Localize table header', 'Choose this option if you want to localize the table header.');
$arrLang['overrideHeaderFieldLabels'] = array('Override table header fields', 'Choose this option if you want to override some table header fields with custom labels.');
$arrLang['headerFieldLabels'] = array('Table header fields', 'Type in the desired changes here.');
$arrLang['headerFieldLabels']['field'] = 'Field';
$arrLang['headerFieldLabels']['label'] = 'Name';


/**
 * Legends
 */
$arrLang['title_legend'] = 'General settings';
$arrLang['export_legend'] = 'Exporter settings';
$arrLang['table_legend'] = 'Operations, tables & fields';


/**
 * Buttons
 */
$arrLang['new'] = array('New Exporter Configuration', 'Create Exporter Configuration');
$arrLang['show'] = array('Exporter Configuration Details', 'Show Exporter Configuration ID %s details');
$arrLang['edit'] = array('Edit Exporter Configuration', 'Edit Exporter Configuration ID %s');
$arrLang['copy'] = array('Copy Exporter Configuration', 'Copy Exporter Configuration ID %s');
$arrLang['delete'] = array('Delete Exporter Configuration', 'Delete Exporter Configuration ID %s');