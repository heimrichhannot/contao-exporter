<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package exporter
 * @author  Oliver Janke <o.janke@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

/**
 * Table tl_exporter
 */
$GLOBALS['TL_DCA']['tl_exporter'] = array(

    // Config
    'config'      => array(
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'onload_callback'  => array(
            array('tl_exporter', 'checkPermission'),
        ),
        'sql'              => array(
            'keys' => array(
                'id' => 'primary',
            ),
        ),

    ),

    // List
    'list'        => array(
        'sorting'           => array(
            'mode'        => 1,
            'flag'        => 11,
            'panelLayout' => 'filter;search,limit',
            'fields'      => array('fileType'),
        ),
        'label'             => array(
            'fields' => array('title'),
            'format' => '%s',
        ),
        'global_operations' => array(
            'all' => array(
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"',
            ),
        ),
        'operations'        => array(
            'edit'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_exporter']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ),
            'copy'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_exporter']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_exporter']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_exporter']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ),
        ),
    ),

    // Palettes
    'palettes'    => array(
        '__selector__'                               => array(
            'fileType',
            'addHeaderToExportTable',
            'overrideHeaderFieldLabels',
            'addJoinTables',
            'type',
            'target',
            'fileNameAddDatime',
        ),
        'default'                                    => '{title_legend},title,type;',
        \HeimrichHannot\Exporter\Exporter::TYPE_LIST => '{title_legend},title,type;' . '{export_legend},target,fileType;'
                                                        . '{table_legend},globalOperationKey,linkedTable,restrictToPids,addUnformattedFields,tableFieldsForExport,addJoinTables,whereClause,orderBy;',
        \HeimrichHannot\Exporter\Exporter::TYPE_ITEM => '{title_legend},title,type;' . '{export_legend},target,fileType;'
                                                        . '{table_legend},linkedTable,skipFields,skipLabels,addJoinTables,whereClause,orderBy;',
    ),

    // Subpalettes
    'subpalettes' => array(
        'fileType_csv'                                                 => 'exporterClass,fieldDelimiter,fieldEnclosure,localizeFields,addHeaderToExportTable',
        'fileType_pdf'                                                 => 'exporterClass,pdfBackground,pdfFonts,pdfMargins,pdfTitle,pdfSubject,pdfCreator,localizeFields,pdfCss,pdfTemplate',
        'fileType_xls'                                                 => 'exporterClass,localizeFields,addHeaderToExportTable',
        'fileType_media'                                               => 'exporterClass,compressionType',
        'addHeaderToExportTable'                                       => 'localizeHeader,overrideHeaderFieldLabels',
        'overrideHeaderFieldLabels'                                    => 'headerFieldLabels',
        'addJoinTables'                                                => 'joinTables',
        'target_' . \HeimrichHannot\Exporter\Exporter::TARGET_DOWNLOAD => 'fileName,fileNameAddDatime',
        'target_' . \HeimrichHannot\Exporter\Exporter::TARGET_FILE     => 'fileDir,useHomeDir,fileSubDirName,fileName,fileNameAddDatime',
        'fileNameAddDatime'                                            => 'fileNameAddDatimeFormat',
    ),

    // Fields
    'fields'      => array(
        'id'                        => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ),
        'tstamp'                    => array(
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'title'                     => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['title'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => array(
                'tl_class'  => 'w50',
                'mandatory' => true,
                'maxlength' => 255,
            ),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'type'                      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['type'],
            'inputType' => 'select',
            'options'   => array(
                \HeimrichHannot\Exporter\Exporter::TYPE_LIST,
                \HeimrichHannot\Exporter\Exporter::TYPE_ITEM,
            ),
            'reference' => &$GLOBALS['TL_LANG']['tl_exporter']['reference'],
            'eval'      => array(
                'mandatory'      => true,
                'tl_class'       => 'w50',
                'submitOnChange' => true,
            ),
            'sql'       => "varchar(16) NOT NULL default 'list'",
        ),

        // table legend
        'linkedTable'               => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['linkedTable'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getLinkedTablesAsOptions'),
            'eval'             => array(
                'chosen'             => true,
                'mandatory'          => true,
                'submitOnChange'     => true,
                'includeBlankOption' => true,
                'tl_class'           => 'w50',
            ),
            'sql'              => "varchar(64) NOT NULL default ''",
        ),
        'globalOperationKey'        => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['globalOperationKey'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getGlobalOperationKeysAsOptions'),
            'eval'             => array(
                'mandatory'          => true,
                'submitOnChange'     => true,
                'includeBlankOption' => true,
                'tl_class'           => 'w50',
            ),
            'sql'              => "varchar(255) NOT NULL default ''",
        ),
        'restrictToPids' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_exporter']['restrictToPids'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'options_callback' => array('\HeimrichHannot\Exporter\Backend', 'getTableArchives'),
            'eval'                    => array('tl_class' => 'long clr', 'style' => 'width: 97%', 'chosen' => true, 'includeBlankOption' => true, 'multiple' => true),
            'sql'                     => "blob NULL"
        ),
        'skipFields'                => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['skipFields'],
            'exclude'          => true,
            'filter'           => true,
            'inputType'        => 'select',
            'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getTableFields'),
            'eval'             => array('multiple' => true, 'chosen' => true, 'tl_class' => 'long', 'style' => 'width: 97%'),
            'sql'              => "blob NULL",
        ),
        'skipLabels'                => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['skipLabels'],
            'exclude'          => true,
            'filter'           => true,
            'inputType'        => 'select',
            'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getTableFields'),
            'eval'             => array('multiple' => true, 'chosen' => true, 'tl_class' => 'long', 'style' => 'width: 97%'),
            'sql'              => "blob NULL",
        ),
        'addUnformattedFields'      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['addUnformattedFields'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array(
                'submitOnChange' => true,
                'tl_class'       => 'w50 clr',
            ),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'tableFieldsForExport'      => array(
            'inputType'        => 'checkboxWizard',
            'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['tableFieldsForExport'],
            'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getTableFields'),
            'exclude'          => true,
            'eval'             => array(
                'multiple'  => true,
                'tl_class'  => 'w50 autoheight clr',
                'mandatory' => true,
            ),
            'sql'              => "blob NULL",
        ),

        // export legend
        'fileType'                  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['fileType'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => array(
                EXPORTER_FILE_TYPE_CSV,
                EXPORTER_FILE_TYPE_PDF,
                EXPORTER_FILE_TYPE_XLS,
                EXPORTER_FILE_TYPE_MEDIA,
            ),
            'reference' => &$GLOBALS['TL_LANG']['tl_exporter']['fileType'],
            'eval'      => array(
                'mandatory'          => true,
                'includeBlankOption' => true,
                'submitOnChange'     => true,
                'tl_class'           => 'w50 clr',
            ),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'exporterClass'             => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['exporterClass'],
            'inputType'        => 'select',
            'eval'             => array('mandatory' => true, 'tl_class' => 'w50', 'decodeEntities' => true),
            'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getExporterClasses'),
            'sql'              => "varchar(255) NOT NULL default ''",
        ),
        'fieldDelimiter'            => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['fieldDelimiter'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'default'   => ',',
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 1,
                'tl_class'  => 'w50 clr',
            ),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'fieldEnclosure'            => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['fieldEnclosure'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'default'   => '"',
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 1,
                'tl_class'  => 'w50',
            ),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'addHeaderToExportTable'    => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['addHeaderToExportTable'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array(
                'submitOnChange' => true,
                'tl_class'       => 'w50 clr',
            ),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'overrideHeaderFieldLabels' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['overrideHeaderFieldLabels'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array(
                'submitOnChange' => true,
                'tl_class'       => 'w50 clr',
            ),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'headerFieldLabels'         => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['headerFieldLabels'],
            'exclude'   => true,
            'inputType' => 'multiColumnWizard',
            'eval'      => array(
                'tl_class'     => 'clr',
                'columnFields' => array(
                    'field' => array(
                        'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['headerFieldLabels']['field'],
                        'exclude'          => true,
                        'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getTableFields'),
                        'inputType'        => 'select',
                        'eval'             => array('style' => 'width: 250px'),
                    ),
                    'label' => array(
                        'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['headerFieldLabels']['label'],
                        'exclude'   => true,
                        'inputType' => 'text',
                        'eval'      => array('style' => 'width: 250px'),
                    ),
                ),
            ),
            'sql'       => "blob NULL",
        ),
        'compressionType'           => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['compressionType'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => array('zip'),
            'reference' => &$GLOBALS['TL_LANG']['tl_exporter']['compressionType'],
            'eval'      => array(
                'mandatory' => true,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'localizeHeader'            => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['localizeHeader'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array(
                'tl_class' => 'w50',
            ),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'localizeFields'            => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['localizeFields'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array(
                'tl_class' => 'w50 clr',
            ),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'target'                    => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['target'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => array(
                \HeimrichHannot\Exporter\Exporter::TARGET_DOWNLOAD,
                \HeimrichHannot\Exporter\Exporter::TARGET_FILE,
            ),
            'reference' => &$GLOBALS['TL_LANG']['tl_exporter']['reference'],
            'eval'      => array(
                'submitOnChange'     => true,
                'mandatory'          => true,
                'includeBlankOption' => true,
                'tl_class'           => 'w50',
            ),
            'sql'       => "varchar(255) NOT NULL default '" . \HeimrichHannot\Exporter\Exporter::TARGET_DOWNLOAD . "'",
        ),
        'fileDir'                   => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['fileDir'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array('fieldType' => 'radio', 'mandatory' => true, 'tl_class' => 'w50 clr'),
            'sql'       => "binary(16) NULL",
        ),
        'useHomeDir'                => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['useHomeDir'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'fileSubDirName'                  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['fileSubDirName'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'fileName'                  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['fileName'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'fileNameAddDatime'         => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['fileNameAddDatime'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange' => true, 'tl_class' => 'w50 clr'),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'fileNameAddDatimeFormat'   => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['fileNameAddDatimeFormat'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'addJoinTables'             => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['addJoinTables'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange' => true, 'tl_class' => 'clr'),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'joinTables'                => array(
            'label'        => &$GLOBALS['TL_LANG']['tl_exporter']['joinTables'],
            'inputType'    => 'fieldpalette',
            'foreignKey'   => 'tl_fieldpalette.id',
            'relation'     => array('type' => 'hasMany', 'load' => 'eager'),
            'sql'          => "blob NULL",
            'fieldpalette' => array(
                'config'   => array(
                    'hidePublished' => true,
                ),
                'list'     => array(
                    'label' => array(
                        'fields' => array('joinTable', 'joinCondition'),
                        'format' => '%s <span style="color:#b3b3b3;padding-left:3px">[%s]</span>',
                    ),
                ),
                'palettes' => array(
                    'default' => 'joinTable,joinCondition',
                ),
                'fields'   => array(
                    'joinTable'     => array(
                        'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['joinTable'],
                        'exclude'          => true,
                        'inputType'        => 'select',
                        'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getAllTablesAsOptions'),
                        'eval'             => array(
                            'includeBlankOption' => true,
                        ),
                        'sql'              => "varchar(255) NOT NULL default ''",
                    ),
                    'joinCondition' => array(
                        'label'       => &$GLOBALS['TL_LANG']['tl_exporter']['joinCondition'],
                        'sorting'     => true,
                        'inputType'   => 'text',
                        'exclude'     => true,
                        'eval'        => array('class' => 'long', 'decodeEntities' => true),
                        'explanation' => 'insertTags',
                        'sql'         => "varchar(255) NOT NULL default ''",
                    ),
                ),
            ),
        ),
        'whereClause'               => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['whereClause'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('tl_class' => 'w50 clr', 'decodeEntities' => true),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'orderBy'                   => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['orderBy'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('tl_class' => 'w50 clr', 'decodeEntities' => true),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'pdfBackground'             => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['pdfBackground'],
            'inputType' => 'fileTree',
            'exclude'   => true,
            'eval'      => array(
                'filesOnly'  => true,
                'extensions' => 'pdf',
                'fieldType'  => 'radio',
                'tl_class'   => 'w50',
            ),
            'sql'       => "binary(16) NULL",
        ),
        'pdfTemplate'               => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['pdfTemplate'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => array('HeimrichHannot\Exporter\Backend', 'getPdfExporterTemplates'),
            'eval'             => array(
                'tl_class'           => 'w50 clr',
                'includeBlankOption' => true,
            ),
            'sql'              => "varchar(128) NOT NULL default ''",
        ),
        'pdfCss'                    => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['pdfCss'],
            'inputType' => 'fileTree',
            'exclude'   => true,
            'eval'      => array(
                'filesOnly'  => true,
                'extensions' => 'css',
                'fieldType'  => 'checkbox',
                'tl_class'   => 'w50',
            ),
            'sql'       => "blob NULL",
        ),
        'pdfFonts'                  => array(
            'label'        => &$GLOBALS['TL_LANG']['tl_exporter']['pdfFonts'],
            'exclude'      => true,
            'inputType'    => 'fieldpalette',
            'foreignKey'   => 'tl_fieldpalette.id',
            'relation'     => array('type' => 'hasMany', 'load' => 'eager'),
            'sql'          => "blob NULL",
            'eval'         => array('tl_class' => 'long clr'),
            'fieldpalette' => array(
                'config'   => array(
                    'hidePublished' => true,
                ),
                'list'     => array(
                    'label' => array(
                        'fields' => array('exporter_pdfFonts_fontName', 'exporter_pdfFonts_fontWeight'),
                        'format' => '%s -> %s',
                    ),
                ),
                'palettes' => array(
                    'default' => 'exporter_pdfFonts_fontName,exporter_pdfFonts_fontWeight,exporter_pdfFonts_file',
                ),
                'fields'   => array(
                    'exporter_pdfFonts_fontName'   => array(
                        'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['exporter_pdfFonts_fontName'],
                        'inputType' => 'text',
                        'eval'      => array('tl_class' => 'clr', 'mandatory' => true),
                        'sql'       => "varchar(255) NOT NULL default ''",
                    ),
                    'exporter_pdfFonts_fontWeight' => array(
                        'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['exporter_pdfFonts_fontWeight'],
                        'inputType' => 'select',
                        'options'   => array('R', 'B', 'I', 'BI'),
                        'reference' => &$GLOBALS['TL_LANG']['tl_exporter']['exporter_pdfFonts_fontWeightOptions'],
                        'eval'      => array('tl_class' => 'clr', 'mandatory' => true, 'includeBlankOption' => true),
                        'sql'       => "varchar(16) NOT NULL default ''",
                    ),
                    'exporter_pdfFonts_file'       => array(
                        'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['exporter_pdfFonts_file'],
                        'inputType' => 'fileTree',
                        'exclude'   => true,
                        'eval'      => array(
                            'filesOnly'  => true,
                            'extensions' => 'ttf',
                            'fieldType'  => 'radio',
                            'mandatory'  => true,
                            'tl_class'   => 'w50',
                        ),
                        'sql'       => "blob NULL",
                    ),
                ),
            ),
        ),
        'pdfMargins'                => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['pdfMargins'],
            'exclude'   => true,
            'inputType' => 'trbl',
            'options'   => array('pt', 'in', 'cm', 'mm'),
            'eval'      => array('includeBlankOption' => true, 'tl_class' => 'w50'),
            'sql'       => "varchar(128) NOT NULL default ''",
        ),
        'pdfTitle'                  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['pdfTitle'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 64, 'tl_class' => 'w50 clr'),
            'sql'       => "varchar(64) NOT NULL default ''",
        ),
        'pdfSubject'                => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['pdfSubject'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'pdfCreator'                => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['pdfCreator'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 64, 'tl_class' => 'w50'),
            'sql'       => "varchar(64) NOT NULL default ''",
        ),
    ),
);

$arrDca = &$GLOBALS['TL_DCA']['tl_exporter'];

if (in_array('protected_homedirs', \ModuleLoader::getActive()))
{
    $arrDca['subpalettes']['target_' . \HeimrichHannot\Exporter\Exporter::TARGET_FILE] =
        str_replace('useHomeDir', 'useHomeDir,useProtectedHomeDir', $arrDca['subpalettes']['target_' . \HeimrichHannot\Exporter\Exporter::TARGET_FILE]);

    $arrDca['fields']['useProtectedHomeDir'] = array(
        'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['useProtectedHomeDir'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => array('tl_class' => 'w50'),
        'sql'       => "char(1) NOT NULL default ''",
    );
}

class tl_exporter extends \Backend
{
    /**
     * Check permissions to edit table tl_exporter
     */
    public function checkPermission()
    {
        if (\BackendUser::getInstance()->isAdmin)
        {
            return;
        }
    }
}