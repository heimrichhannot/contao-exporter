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
		'__selector__' => array('fileType', 'addHeaderToExportTable', 'overrideHeaderFieldLabels'),
		'default' => '
		{title_legend},title;
		{export_legend},target,fileType;
		{table_legend},globalOperationKey,linkedTable,tableFieldsForExport;',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'fileType_csv' => 'fieldDelimiter,fieldEnclosure,localizeFields,addHeaderToExportTable',
		'fileType_xls' => 'localizeFields,addHeaderToExportTable',
		'fileType_media' => 'compressionType',
		'addHeaderToExportTable' => 'localizeHeader,overrideHeaderFieldLabels',
		'overrideHeaderFieldLabels' => 'headerFieldLabels'
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
			'options_callback' => array('tl_exporter', 'getGlobalOperationKeysAsOptions'),
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
			'options_callback' => array('tl_exporter', 'getLinkedTablesAsOptions'),
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
			'options_callback' => array('tl_exporter', 'getTableFieldsAndUnformatted'),
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
				'submitOnChange' => true,
				'tl_class' => 'w50 clr'),
			'sql' => "char(1) NOT NULL default ''"
		),
		'overrideHeaderFieldLabels' => array
		(
			'label' => &$GLOBALS['TL_LANG']['tl_exporter']['overrideHeaderFieldLabels'],
			'exclude' => true,
			'inputType' => 'checkbox',
			'eval' => array(
					'submitOnChange' => true,
					'tl_class' => 'w50 clr'),
			'sql' => "char(1) NOT NULL default ''"
		),
		'headerFieldLabels' => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['headerFieldLabels'],
			'exclude'   => true,
			'inputType' => 'multiColumnWizard',
			'eval'      => array(
				'tl_class'     => 'clr',
				'columnFields' => array(
					'field' => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['headerFieldLabels']['field'],
						'exclude'   => true,
						'options_callback' => array('tl_exporter', 'getTableFields'),
						'inputType' => 'select',
						'eval'      => array('style' => 'width: 250px'),
					),
					'label' => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_exporter']['headerFieldLabels']['label'],
						'exclude'   => true,
						'inputType' => 'text',
						'eval'      => array('style' => 'width: 250px'),
					),
				)
			),
			'sql'       => "blob NULL"
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
				'tl_class' => 'w50 clr'),
			'sql' => "char(1) NOT NULL default ''"
		),
		'target' => array
		(
			'label'  => &$GLOBALS['TL_LANG']['tl_exporter']['target'],
			'exclude' => true,
			'inputType' => 'select',
			'options' => array('download'),
			'reference' => &$GLOBALS['TL_LANG']['tl_exporter']['target'],
			'eval' => array
			(
				'mandatory' => true,
				'includeBlankOption' => true,
				'tl_class' => 'w50',
			),
			'sql' => "varchar(255) NOT NULL default ''"
		),
	)
);

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

	public static function getTableFields($objDc)
	{
		return static::doGetTableFields($objDc->activeRecord->linkedTable);
	}

	public static function getTableFieldsAndUnformatted(\DataContainer $objDc)
	{
		return static::doGetTableFields($objDc->activeRecord->linkedTable,
				$objDc->activeRecord->fileType != EXPORTER_FILE_TYPE_MEDIA);
	}

	public static function doGetTableFields($strTable, $blnIncludeUnformatted = false)
	{
		$arrOptions = array();
		$arrSkipFields = array('index');
		$strTableName = $strTable;

		if ($strTableName)
		{
			$arrFields = \Database::getInstance()->listFields($strTableName);

			if (!is_array($arrFields) || empty($arrFields))
			{
				return $arrOptions;
			}

			foreach ($arrFields as $arrField)
			{
				if (!in_array($arrField['type'], $arrSkipFields))
					$arrOptions[$arrField['name']] = $arrField['name'] . ' [' . $arrField['type'] . ']';
			}
		}

		if ($blnIncludeUnformatted)
		{
			$arrOptionsRawKeys = array_map(function($val) {
				return $val . EXPORTER_RAW_FIELD_SUFFIX;
			}, array_keys($arrOptions));

			$arrOptionsRawValues = array_map(function($val) {
				return $val . $GLOBALS['TL_LANG']['MSC']['exporter']['unformatted'];
			}, array_values($arrOptions));

			$arrOptions += array_combine($arrOptionsRawKeys, $arrOptionsRawValues);
		}

		asort($arrOptions);

		return $arrOptions;
	}

	/**
	 * Searches through all backend modules to find global operation keys and returns a filtered list
	 *
	 * @return array
	 */
	public static function getGlobalOperationKeysAsOptions()
	{
		$arrGlobalOperations = array();
		$arrSkipKeys = array('callback', 'generate', 'icon', 'import', 'javascript', 'stylesheet', 'table', 'tables');

		foreach ($GLOBALS['BE_MOD'] as $arrSection)
		{
			foreach ($arrSection as $arrModule)
			{
				foreach ($arrModule as $strKey => $varValue)
				{
					if (!in_array($strKey, $arrGlobalOperations) && !in_array($strKey, $arrSkipKeys))
					{
						$arrGlobalOperations[] = $strKey;
					}
				}
			}
		}
		sort($arrGlobalOperations);

		return $arrGlobalOperations;
	}


	/**
	 * Searches through all backend modules to find the linked tables for the selected global operation key
	 *
	 * @param \DataContainer $dc
	 * @return array
	 */
	public static function getLinkedTablesAsOptions(\DataContainer $dc)
	{
		$arrTables = array();
		$strGlobalOperationKey = $dc->activeRecord->globalOperationKey;

		if ($strGlobalOperationKey)
		{
			foreach ($GLOBALS['BE_MOD'] as $arrSection)
			{
				foreach ($arrSection as $strModule => $arrModule)
				{
					foreach ($arrModule as $strKey => $varValue)
					{
						if ($strKey === $strGlobalOperationKey)
						{
							$arrTables[$strModule] = $arrModule['tables'];
						}
					}
				}
			}
		}

		return $arrTables;
	}
}
