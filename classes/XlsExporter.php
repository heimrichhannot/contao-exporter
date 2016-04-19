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

class XlsExporter extends Exporter
{
	protected $blnAddHeader;
	protected $blnLocalizeHeader;
	protected $blnLocalizeFields;
	protected $strDelimiter;
	protected $strEnclosure;
	protected $strExportTarget;

	protected $strTable;
	protected $strFileName;
	protected $objXls;

	protected $arrHeaderFields = array();
	protected $arrExportFields = array();

	public function __construct()
	{
		$this->objXls = new \PHPExcel();
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
		$this->blnLocalizeFields = $arrOptions['localizeFields'];
		$this->overrideHeaderFieldLabels = $arrOptions['overrideHeaderFieldLabels'];
		$this->headerFieldLabels = $arrOptions['headerFieldLabels'];
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
			$this->setHeaderFields($this->arrExportFields);
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
	 * Gets data from the database and writes it to the xls file for download
	 */
	protected function exportToDownload()
	{
		$strTmpFile = 'system/tmp/' . $this->strFileName;
		$arrExportFields = array();
		$arrDca = $GLOBALS['TL_DCA'][$this->strTable];
		$arrDcaFields = $arrDca['fields'];

		foreach ($this->arrExportFields as $strField)
		{
			if (strpos($strField, EXPORTER_RAW_FIELD_SUFFIX) !== false)
				$arrExportFields[] = str_replace(EXPORTER_RAW_FIELD_SUFFIX, '', $strField) . ' AS ' . $strField;
			else
				$arrExportFields[] = $strField;
		}

		$strQuery = 'SELECT ' . implode(',', $arrExportFields) .
					' FROM ' . $this->strTable;

		if (TL_MODE == 'BE')
		{
			$strAct = \Input::get('act');
			$intPid = \Input::get('id');

			if ($intPid && !$strAct && $arrDcaFields && $arrDca['config']['ptable'])
			{
				$strQuery .= ' WHERE pid = ' . $intPid;
			}
		}

		$objDbResult = \Database::getInstance()->prepare($strQuery)->execute();

		if (!$objDbResult->numRows > 0)
			return;

		$intCol = 0;
		$intRow = 1;

		// write header fields
		if ($this->blnAddHeader)
		{
			foreach ($this->arrHeaderFields as $varValue)
			{
				$this->objXls->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intCol, $intRow, $varValue);
				$intCol++;
			}
			$intRow++;
		}

		// write fields to file
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

				$this->objXls->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intCol, $intRow, html_entity_decode($varValue));
				$this->objXls->getActiveSheet()->getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex($intCol))->setAutoSize(true);
				$intCol++;
			}

			$this->objXls->getActiveSheet()->getRowDimension($intRow)->setRowHeight(-1);
			$intRow++;
		}

		$this->objXls->setActiveSheetIndex(0);
		$this->objXls->getActiveSheet()->setTitle('Export');

		// send file to browser
		$objWriter = \PHPExcel_IOFactory::createWriter($this->objXls, 'Excel5');
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
		return 'export-' . $this->getArchiveName() . '_' . date('Y-m-d_H-i', time()) . '.xls';
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