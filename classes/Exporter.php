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

use HeimrichHannot\Haste\Util\Arrays;

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


	protected function setHeaderFields()
	{
		$arrFields = array();

		\System::loadLanguageFile($this->strTable);

		foreach ($this->arrExportFields as $strField)
		{
			$blnRawField = strpos($strField, EXPORTER_RAW_FIELD_SUFFIX) !== false;
			$strRawFieldName = str_replace(EXPORTER_RAW_FIELD_SUFFIX, '', $strField);

			$strFieldName = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$blnRawField ? $strRawFieldName : $strField]['label'][0];
			$strLabel = $strField;

			if ($this->overrideHeaderFieldLabels && ($arrRow =
					Arrays::getRowInMcwArray('field', $strField, deserialize($this->headerFieldLabels, true))) !== false)
			{
				$strLabel = $arrRow['label'];
			}
			elseif ($this->blnLocalizeHeader && $strFieldName)
			{
				$strLabel = $strFieldName;
			}

			$arrFields[$strField] = strip_tags(html_entity_decode($strLabel)) . ($blnRawField ? $GLOBALS['TL_LANG']['MSC']['exporter']['unformatted'] : '');
		}

		if (isset($GLOBALS['TL_HOOKS']['exporter_modifyHeaderFields']) && is_array($GLOBALS['TL_HOOKS']['exporter_modifyXlsHeaderFields']))
		{
			foreach ($GLOBALS['TL_HOOKS']['exporter_modifyHeaderFields'] as $callback)
			{
				$objCallback = \System::importStatic($callback[0]);
				$arrFields = $objCallback->$callback[1]($arrFields, $this);
			}
		}

		$this->arrHeaderFields = $arrFields;
	}


	/**
	 * Gets the export configs for selected key and table and calls the needed exporter
	 */
	public function export()
	{
		$this->strGlobalOperationKey = \Input::get('key');
		$this->strExportTable = \Input::get('table');

		\Controller::loadDataContainer($this->strExportTable);

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
						case EXPORTER_FILE_TYPE_CSV:
							$objExporter = new CsvExporter();
							$objExporter->setOptions($this->setOptionsForExporter($objExportConfig));
							$objExporter->setExportFields($objExportConfig->tableFieldsForExport);
							$objExporter->export($this->strExportTable);
							break;
						case EXPORTER_FILE_TYPE_XLS:
							$objExporter = new XlsExporter();
							$objExporter->setOptions($this->setOptionsForExporter($objExportConfig));
							$objExporter->setExportFields($objExportConfig->tableFieldsForExport);
							$objExporter->export($this->strExportTable);
							break;
						case EXPORTER_FILE_TYPE_MEDIA:
							$objExporter = new MediaExporter();
							$objExporter->setOptions($this->setOptionsForExporter($objExportConfig));
							$objExporter->setExportFields($objExportConfig->tableFieldsForExport);
							$objExporter->export($this->strExportTable);
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
		$arrOptions['overrideHeaderFieldLabels'] = $objExportConfig->overrideHeaderFieldLabels;
		$arrOptions['headerFieldLabels'] = $objExportConfig->headerFieldLabels;
		$arrOptions['localizeFields'] = $objExportConfig->localizeFields;
		$arrOptions['delimiter'] = $objExportConfig->fieldDelimiter;
		$arrOptions['enclosure'] = $objExportConfig->fieldEnclosure;
		$arrOptions['exportTarget'] = 'download'; // for future
		$arrOptions['compressionType'] = $objExportConfig->compressionType; // for future

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