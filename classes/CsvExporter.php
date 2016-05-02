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

use Contao\DC_Table;
use HeimrichHannot\Haste\Util\Files;

class CsvExporter extends Exporter
{
	protected $blnAddHeader;
	protected $blnLocalizeHeader;
	protected $blnLocalizeFields;
	protected $strDelimiter;
	protected $strEnclosure;
	protected $strExportTarget;

	protected $strTable;
	protected $strFileName;
	protected $objCsv;

	protected $arrHeaderFields = array();
	protected $arrExportFields = array();

	public function __construct() {
		$this->objCsv = new \PHPExcel();
	}


	/**
	 * Sets the options for the exporter
	 *
	 * @param array $arrOptions
	 */
	public function setOptions(array $arrOptions=array())
	{
		if (empty($arrOptions)) return;

		$this->blnAddHeader = $arrOptions['addHeader'];
		$this->blnLocalizeHeader = $arrOptions['localizeHeader'];
		$this->overrideHeaderFieldLabels = $arrOptions['overrideHeaderFieldLabels'];
		$this->headerFieldLabels = $arrOptions['headerFieldLabels'];
		$this->blnLocalizeFields = $arrOptions['localizeFields'];
		$this->strDelimiter = $arrOptions['delimiter'];
		$this->strEnclosure = $arrOptions['enclosure'];
		$this->strExportTarget = $arrOptions['exportTarget'];
	}


	/**
	 * Sets the fields
	 *
	 * @param $varFields
	 */
	public function setExportFields($varFields)
	{
		if (is_array($varFields))
			$this->arrExportFields = $varFields;
		else
			$this->arrExportFields = deserialize($varFields, true);
	}


	/**
	 * Sets the file name
	 *
	 * @param $strFileName
	 */
	public function setFileName($strFileName)
	{
		$this->strFileName = $strFileName;
	}


	/**
	 * Prepares the export
	 *
	 * @param $strTable
	 */
	public function export($strTable)
	{
		$this->strTable = $strTable;

		if (!$this->strFileName)
		{
			$this->strFileName = $this->buildFileName();
		}

		if ($this->blnAddHeader)
		{
			$this->setHeaderFields();
		}

		switch($this->strExportTarget)
		{
			case 'download' :
				$this->exportToDownload();
				break;

			default:
				break;
		}
	}


	/**
	 * Gets data from the database and writes it to the csv file for download
	 */
	protected function exportToDownload()
	{
		$strTmpFile = 'system/tmp/' . $this->strFileName;

		$arrExportFields = array();
		foreach ($this->arrExportFields as $strField)
		{
			if (strpos($strField, EXPORTER_RAW_FIELD_SUFFIX) !== false)
				$arrExportFields[] = str_replace(EXPORTER_RAW_FIELD_SUFFIX, '', $strField) . ' AS ' . $strField;
			else
				$arrExportFields[] = $strField;
		}

		$objDbResult = \Database::getInstance()->prepare(
				"SELECT " . implode(',', $arrExportFields) .
				" FROM " . $this->strTable
		)->execute();

		if (!$objDbResult->numRows > 0)
			return;

		$arrDcaFields = $GLOBALS['TL_DCA'][$this->strTable]['fields'];
		$intCol = 0;
		$intRow = 1;

		// write header fields
		if ($this->blnAddHeader)
		{
			foreach ($this->arrHeaderFields as $varValue)
			{
				$this->objCsv->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intCol, $intRow, $varValue);
				$this->objCsv->getActiveSheet()->getStyle(\PHPExcel_Cell::stringFromColumnIndex($intCol))->getAlignment()->setWrapText(true);
				$intCol++;
			}
			$intRow++;
		}

		// write to file
		while($objDbResult->next())
		{
			$arrRow = $objDbResult->row();
			$intCol = 0;

			foreach ($arrRow as $key => $varValue)
			{
				$objDc = new DC_Table($this->strTable);
				$objDc->activeRecord = $objDbResult;
				$varValue = $this->blnLocalizeFields ? Helper::getFormatedValueByDca($varValue, $arrDcaFields[$key], $objDc) : $varValue;
				if (is_array($varValue))
					$varValue = Helper::flattenArray($varValue);
				if($key == 'tstamp')$varValue= date(\Config::get('dateFormat'), $varValue);
				$this->objCsv->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intCol, $intRow, html_entity_decode($varValue));
				$this->objCsv->getActiveSheet()->getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex($intCol))->setAutoSize(true);
				$this->objCsv->getActiveSheet()->getStyle(\PHPExcel_Cell::stringFromColumnIndex($intCol))->getAlignment()->setWrapText(true);
				$intCol++;
			}

			$this->objCsv->getActiveSheet()->getRowDimension($intRow)->setRowHeight(-1);
			$intRow++;
		}

		$this->objCsv->setActiveSheetIndex(0);
		$this->objCsv->getActiveSheet()->setTitle('Export');

		// send file to browser
		$objWriter = \PHPExcel_IOFactory::createWriter($this->objCsv, 'CSV')
			->setDelimiter($this->strDelimiter)
			->setEnclosure($this->strEnclosure)
			->setSheetIndex(0);
		$objWriter->save(TL_ROOT . '/' . $strTmpFile);

		$objFile = new \File($strTmpFile);
		$objFile->sendToBrowser();
	}


	/**
	 * Builds the name for the export file
	 *
	 * @return string
	 */
	protected function buildFileName()
	{
		return 'export-' . $this->getArchiveName() . '_' . date('Y-m-d_H-i', time()) . '.csv';
	}

	public function getArchiveName()
	{
		$strPTable = $GLOBALS['TL_DCA'][$this->strTable]['config']['ptable'];
		$intPid = \Input::get('id');

		if($strPTable)
		{
			$strQuery = 'SELECT title FROM ' . $strPTable . ' WHERE id = ' . $intPid;

			$objDbResult = \Database::getInstance()->prepare($strQuery)->execute();

			while($objDbResult->next())
			{
				return $objDbResult->title;
			}


		}
		else{
			return $this->strTable;
		}
	}
}