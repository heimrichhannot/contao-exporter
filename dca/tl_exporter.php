<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package exporter
 * @author Oliver Janke <o.janke@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

/**
 * Table tl_exporter
 */
$GLOBALS['TL_DCA']['tl_exporter'] = array(

	// Config
	'config'   => array
	(
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'onload_callback' => array
		(
			array('tl_exporter', 'checkPermission'),
		),
		'sql'              => array(
			'keys' => array(
				'id' => 'primary',
			)
		),

	),

	// List
	'list'     => array
	(
		'sorting'           => array
		(
			'mode'        => 1,
			'flag'        => 11,
			'panelLayout' => 'filter;search,limit',
			'fields'      => array('globalOperationKey')
		),
		'label'             => array
		(
			'fields' => array('title'),
			'format' => '%s'
		),
		'global_operations' => array
		(
			'all' => array(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'   => array(
				'label' => &$GLOBALS['TL_LANG']['tl_exporter']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'copy'   => array(
				'label' => &$GLOBALS['TL_LANG']['tl_exporter']['copy'],
				'href'  => 'act=copy',
				'icon'  => 'copy.gif'
			),
			'delete' => array(
				'label'      => &$GLOBALS['TL_LANG']['tl_exporter']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show'   => array(
				'label' => &$GLOBALS['TL_LANG']['tl_exporter']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__' => array('fileType'),
		'default' => '
		{title_legend},title;
		{export_legend},fileType;
		{table_legend},globalOperationKey,linkedTable,tableFieldsForExport;',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'fileType_csv' => 'fieldDelimiter,fieldEnclosure,addHeaderToExportTable,localizeHeader,localizeFields',
		'fileType_xls' => 'addHeaderToExportTable,localizeHeader,localizeFields',
		'fileType_media' => 'compressionType'
	),

	// Fields
	'fields'   => array
	(
		'id'      => array
		(
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp'  => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'title'   => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array
			(
				'mandatory' => true,
				'maxlength' => 255
			),
			'sql'       => "varchar(255) NOT NULL default ''"
		),

		// table legend
		'globalOperationKey' => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_exporter']['globalOperationKey'],
			'exclude'          => true,
			'inputType'        => 'select',
			'options_callback' => array('HeimrichHannot\Exporter\Exporter', 'getGlobalOperationKeysAsOptions'),
			'eval'             => array
			(
				'mandatory'          => true,
				'submitOnChange'     => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50',
			),
			'sql'              => "varchar(255) NOT NULL default ''"
		),
		'linkedTable' => array
		(
			'label'  => &$GLOBALS['TL_LANG']['tl_exporter']['linkedTable'],
			'exclude' => true,
			'inputType' => 'select',
			'options_callback' => array('HeimrichHannot\Exporter\Exporter', 'getLinkedTablesAsOptions'),
			'eval' => array
			(
				'mandatory' => true,
				'submitOnChange' => true,
				'includeBlankOption' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(255) NOT NULL default ''"
		),
		'tableFieldsForExport' => array
		(
			'inputType' => 'checkboxWizard',
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['tableFieldsForExport'],
			'options_callback' => array('HeimrichHannot\Exporter\Exporter', 'getTableFields'),
			'exclude' => true,
			'eval' => array
			(
				'multiple' => true,
				'tl_class' => 'w50 autoheight clr',
				'mandatory' => true
			),
			'sql'  => "blob NULL",
		),

		// export legend
		'fileType' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['fileType'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array(EXPORTER_FILE_TYPE_CSV, EXPORTER_FILE_TYPE_XLS, EXPORTER_FILE_TYPE_MEDIA),
			'reference' => &$GLOBALS['TL_LANG']['tl_exporter']['fileType'],
			'eval' => array
			(
				'mandatory' => true,
				'includeBlankOption' => true,
				'submitOnChange' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(255) NOT NULL default ''"
		),
		'fieldDelimiter'   => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['fieldDelimiter'],
			'exclude' => true,
			'search' => true,
			'sorting' => true,
			'flag' => 1,
			'inputType' => 'text',
			'default' => ',',
			'eval' => array
			(
				'mandatory' => true,
				'maxlength' => 1,
				'tl_class' => 'w50 clr',
			),
			'sql' => "char(1) NOT NULL default ''"
		),
		'fieldEnclosure'   => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['fieldEnclosure'],
			'exclude' => true,
			'search' => true,
			'sorting' => true,
			'flag' => 1,
			'inputType' => 'text',
			'default' => '"',
			'eval' => array
			(
				'mandatory' => true,
				'maxlength' => 1,
				'tl_class' => 'w50',
			),
			'sql' => "char(1) NOT NULL default ''"
		),
		'addHeaderToExportTable' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['addHeaderToExportTable'],
			'exclude' => true,
			'inputType' => 'checkbox',
			'eval' => array(
				'tl_class' => 'w50 clr'),
			'sql' => "char(1) NOT NULL default ''"
		),
		'compressionType' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['compressionType'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array('zip'),
			'reference' => &$GLOBALS['TL_LANG']['tl_exporter']['compressionType'],
			'eval' => array
			(
				'mandatory' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(255) NOT NULL default ''"
		),
		'localizeHeader' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['localizeHeader'],
			'exclude' => true,
			'inputType' => 'checkbox',
			'eval' => array(
				'tl_class' => 'w50'),
			'sql' => "char(1) NOT NULL default ''"
		),
		'localizeFields' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['localizeFields'],
			'exclude' => true,
			'inputType' => 'checkbox',
			'eval' => array(
				'tl_class' => 'w50'),
			'sql' => "char(1) NOT NULL default ''"
		)
	)
);

class tl_exporter extends Backend
{
	/**
	 * Check permissions to edit table tl_exporter
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}
	}
}
