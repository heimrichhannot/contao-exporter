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

class XlsExporter
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
	 * Sets the option for the CSV-exporter
	 *
	 * @param array $arrOptions
	 */
	public function setOptions(array $arrOptions=array())
	{
		if (empty($arrOptions)) return;

		$this->blnAddHeader = $arrOptions['addHeader'];
		$this->blnLocalizeHeader = $arrOptions['localizeHeader'];
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
	 * Sets and localizes the header fields
	 *
	 * @param $arrExportFields
	 */
	protected function setHeaderFields($arrExportFields)
	{
		$arrFields = array();

		\System::loadLanguageFile($this->strTable);

		foreach ($arrExportFields as $strField)
		{
			$strFieldName = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strField]['label'][0];
			$arrFields[$strField] = strip_tags(($this->blnLocalizeHeader && $strFieldName) ? $strFieldName : $strField);
		}

		if (isset($GLOBALS['TL_HOOKS']['exporter_modifyXlsHeaderFields']) && is_array($GLOBALS['TL_HOOKS']['exporter_modifyXlsHeaderFields']))
		{
			foreach ($GLOBALS['TL_HOOKS']['exporter_modifyXlsHeaderFields'] as $callback)
			{
				$objCallback = \System::importStatic($callback[0]);
				$arrFields = $objCallback->$callback[1]($arrFields);
			}
		}

		$this->arrHeaderFields = $arrFields;
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
		$objDbResult = \Database::getInstance()->prepare(
			"SELECT " . implode(',', $this->arrExportFields) .
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
				$this->objXls->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intCol, $intRow, $varValue);
				$this->objXls->getActiveSheet()->getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex($intCol))->setAutoSize(true);
				$intCol++;
			}

			$this->objXls->getActiveSheet()->getRowDimension($intRow)->setRowHeight(-1);
			$intRow++;
		}

		$this->objXls->setActiveSheetIndex(0);
		$this->objXls->getActiveSheet()->setTitle('Export');

		// send file to browser
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=' . $this->strFileName);

		$objWriter = \PHPExcel_IOFactory::createWriter($this->objXls, 'Excel5');
		$objWriter->save('php://output');
	}


	/**
	 * Builds the name for the export file
	 *
	 * @return string
	 */
	protected function buildFileName()
	{
		return 'export-' . $this->strTable . '_' . date('Y-m-d_H-i', time()) . '.xls';
	}
}