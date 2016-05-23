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
use HeimrichHannot\Haste\Util\Files;

abstract class Exporter extends \Controller
{
	protected $objConfig;
	
	/**
	 * @var \PHPExcel
	 */
	protected $objPhpExcel;
	protected $strWriterOutputType;
	
	protected $strFilename;
	protected $strFileType;

	public function __construct($objConfig)
	{
		$this->objConfig = $objConfig;
		$arrSkipFields = array('id', 'tstamp', 'title');

		foreach ($objConfig->row() as $strField => $varValue)
		{
			if (in_array($strField, $arrSkipFields))
				continue;

			$this->{$strField} = $varValue;
		}

		\Controller::loadDataContainer($this->linkedTable);
		\System::loadLanguageFile($this->linkedTable);
	}

	public function export()
	{
		if (!$this->strFilename)
		{
			$this->strFilename = $this->buildFilename();
		}

		switch ($this->target)
		{
			default:
				$this->exportToDownload();
				break;
		}
	}

	public function setFilename($strFilename)
	{
		$this->strFilename = $strFilename;
	}

	protected function buildFilename()
	{
		return 'export-' . Files::sanitizeFileName(Helper::getArchiveName($this->linkedTable)) . '_' . date('Y-m-d_H-i', time()) . '.' . $this->strFileType;
	}

	protected function setHeaderFields()
	{
		$arrFields = array();

		foreach (deserialize($this->tableFieldsForExport, true) as $strField)
		{
			$blnRawField = strpos($strField, EXPORTER_RAW_FIELD_SUFFIX) !== false;
			$strRawFieldName = str_replace(EXPORTER_RAW_FIELD_SUFFIX, '', $strField);

			$strFieldName = $GLOBALS['TL_DCA'][$this->linkedTable]['fields'][$blnRawField ? $strRawFieldName : $strField]['label'][0];
			$strLabel = $strField;

			if ($this->overrideHeaderFieldLabels && ($arrRow =
					Arrays::getRowInMcwArray('field', $strField, deserialize($this->headerFieldLabels, true))) !== false)
			{
				$strLabel = $arrRow['label'];
			}
			elseif ($this->localizeHeader && $strFieldName)
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

	protected function exportToDownload()
	{
		if (!$this->objPhpExcel)
			die('Define objPhpExcel in your Exporter class or overwrite exportToDownload.');

		$strTmpFile = 'system/tmp/' . $this->strFilename;
		$arrExportFields = array();
		$arrDca = $GLOBALS['TL_DCA'][$this->linkedTable];

		foreach (deserialize($this->tableFieldsForExport, true) as $strField)
		{
			if (strpos($strField, EXPORTER_RAW_FIELD_SUFFIX) !== false)
				$arrExportFields[] = str_replace(EXPORTER_RAW_FIELD_SUFFIX, '', $strField) . ' AS ' . $strField;
			else
				$arrExportFields[] = $strField;
		}

		$strQuery = 'SELECT ' . implode(',', $arrExportFields) .
			' FROM ' . $this->linkedTable;

		if (TL_MODE == 'BE')
		{
			$strAct = \Input::get('act');
			$intPid = \Input::get('id');

			if ($intPid && !$strAct && is_array($arrDca['fields']) && $arrDca['config']['ptable'])
			{
				$strQuery .= ' WHERE pid = ' . $intPid;
			}
		}

		$objDbResult = \Database::getInstance()->prepare($strQuery)->execute();

		if (!$objDbResult->numRows > 0)
			return;

		$intCol = 0;
		$intRow = 1;

		// header
		if ($this->objConfig->addHeaderToExportTable)
		{
			foreach ($this->arrHeaderFields as $varValue)
			{
				$this->objPhpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intCol, $intRow, $varValue);

				$this->processHeaderRow($intCol);

				$intCol++;
			}
			$intRow++;
		}

		// body
		while($objDbResult->next())
		{
			$arrRow = $objDbResult->row();
			$intCol = 0;

			foreach ($arrRow as $key => $varValue)
			{

				$objDc = new \DC_Table($this->linkedTable);
				$objDc->activeRecord = $objDbResult;
				$varValue = $this->localizeFields ? Helper::getFormatedValueByDca($varValue, $arrDca['fields'][$key], $objDc) : $varValue;
				if (is_array($varValue))
					$varValue = Helper::flattenArray($varValue);

				$this->objPhpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intCol, $intRow, html_entity_decode($varValue));
				$this->objPhpExcel->getActiveSheet()->getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex($intCol))->setAutoSize(true);

				$this->processBodyRow($intCol);

				$intCol++;
			}
			$this->objPhpExcel->getActiveSheet()->getRowDimension($intRow)->setRowHeight(-1);
			$intRow++;
		}

		$this->objPhpExcel->setActiveSheetIndex(0);
		$this->objPhpExcel->getActiveSheet()->setTitle('Export');

		// send file to browser
		$objWriter = \PHPExcel_IOFactory::createWriter($this->objPhpExcel, $this->strWriterOutputType);

		$this->updateWriter($objWriter);

		$objWriter->save(TL_ROOT . '/' . $strTmpFile);

		$objFile = new \File($strTmpFile);
		$objFile->sendToBrowser();
	}

	public function processHeaderRow($intCol) {}

	public function processBodyRow($intCol) {}

	public function updateWriter($objWriter) {}

}