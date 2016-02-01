<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @author  Oliver Janke <o.janke@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Exporter;

class Exporter
{
	protected $strGlobalOperationKey;
	protected $strExportTable;

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


	/**
	 * Gets the fields from the selected database table
	 *
	 * @param \DataContainer $dc
	 * @return array
	 */
	public static function getTableFields(\DataContainer $dc)
	{
		$arrOptions = array();
		$arrSkipFields = array('index');
		$strTableName = $dc->activeRecord->linkedTable;

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

		$arrOptionsRawKeys= array_map(function($val) {
			return $val . EXPORTER_RAW_FIELD_SUFFIX;
		}, array_keys($arrOptions));

		$arrOptionsRawValues = array_map(function($val) {
			return $val . $GLOBALS['TL_LANG']['MSC']['exporter']['unformatted'];
		}, array_values($arrOptions));

		$arrOptions += array_combine($arrOptionsRawKeys, $arrOptionsRawValues);

		asort($arrOptions);

		return $arrOptions;
	}


	/**
	 * Gets the export configs for selected key and table and calls the needed exporter
	 */
	public function export()
	{
		$this->strGlobalOperationKey = \Input::get('key');
		$this->strExportTable = \Input::get('table');

		if (isset($this->strExportTable) && isset($this->strGlobalOperationKey))
		{
			$arrExportConfigs = ExporterModel::findByKeyAndTable($this->strGlobalOperationKey, $this->strExportTable);

			if (!$arrExportConfigs)
			{
				if (empty($_SESSION['TL_ERROR']))
				{
					\Message::addError($GLOBALS['TL_LANG']['MSC']['exporter']['noConfigFound']);
					\Controller::redirect($_SERVER['HTTP_REFERER']);
				}
			}
			else
			{
				foreach ($arrExportConfigs as $objExportConfig)
				{
					switch($objExportConfig->fileType)
					{
						case 'csv' :
							$objCsvExporter = new CsvExporter();
							$objCsvExporter->setOptions($this->setOptionsForExporter($objExportConfig));
							$objCsvExporter->setExportFields($objExportConfig->tableFieldsForExport);
							$objCsvExporter->export($this->strExportTable);
							break;

						case 'xls' :
							$objXlsExporter = new XlsExporter();
							$objXlsExporter->setOptions($this->setOptionsForExporter($objExportConfig));
							$objXlsExporter->setExportFields($objExportConfig->tableFieldsForExport);
							$objXlsExporter->export($this->strExportTable);
							break;

						default :
							continue;
					}
				}
				die();
			}
		}
	}


	/**
	 * Creates an array that contains the needed options for the different exporters from the exporter config object.
	 *
	 * @param $objExportConfig
	 * @return array
	 */
	protected function setOptionsForExporter($objExportConfig)
	{
		$arrOptions = array();

		$arrOptions['addHeader'] = $objExportConfig->addHeaderToExportTable;
		$arrOptions['localizeHeader'] = $objExportConfig->localizeHeader;
		$arrOptions['localizeFields'] = $objExportConfig->localizeFields;
		$arrOptions['delimiter'] = $objExportConfig->fieldDelimiter;
		$arrOptions['enclosure'] = $objExportConfig->fieldEnclosure;
		$arrOptions['exportTarget'] = 'download'; // for future

		return $arrOptions;
	}

	public static function getGlobalOperation($strName, $strLabel = '', $strIcon = '')
	{
		$arrOperation = array
		(
			'label'      => &$strLabel,
			'href'       => 'key=' . $strName,
			'class'      => 'header_' . $strName . '_entities',
			'icon'       => $strIcon,
			'attributes' => 'onclick="Backend.getScrollOffset()"'
		);

		return $arrOperation;
	}

	public static function getBackendModule()
	{
		return array('\HeimrichHannot\Exporter\Exporter', 'export');
	}

}