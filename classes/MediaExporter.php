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
use Contao\ZipWriter;
use HeimrichHannot\Haste\Util\Files;

class MediaExporter
{
	protected $strExportTarget;
	protected $strCompressionType;

	protected $strTable;
	protected $strFileName;

	protected $arrExportFields = array();

	/**
	 * Sets the options for the exporter
	 *
	 * @param array $arrOptions
	 */
	public function setOptions(array $arrOptions=array())
	{
		if (empty($arrOptions)) return;

		$this->strExportTarget = $arrOptions['exportTarget'];
		$this->strCompressionType = $arrOptions['compressionType'];
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
	 * Gets data from the database and writes it as file to the zip file for download
	 */
	protected function exportToDownload()
	{
		$strTmpFile = 'system/tmp/' . $this->strFileName;
		$strTmpFolder = str_replace('.' . $this->strCompressionType, '', $strTmpFile);

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

		switch ($this->strCompressionType)
		{
			default:
				$objZip = new ZipWriter($strTmpFile);
				break;
		}

		$arrDcaFields = $GLOBALS['TL_DCA'][$this->strTable]['fields'];

		// write files
		while ($objDbResult->next())
		{
			$arrRow = $objDbResult->row();

			foreach ($arrRow as $key => $varValue)
			{
				$objDc = new DC_Table($this->strTable);
				$objDc->activeRecord = $objDbResult;
				$varValue = Helper::getFormatedValueByDca($varValue, $arrDcaFields[$key], $objDc);

				if (!is_array($varValue))
					$varValue = array($varValue);

				foreach ($varValue as $strPath)
				{
					if ($strPath && ($objFile = new \File($strPath)) !== null)
					{
						if (isset($GLOBALS['TL_HOOKS']['exporter_modifyMediaFilename']) && is_array($GLOBALS['TL_HOOKS']['exporter_modifyMediaFilename']))
						{
							foreach ($GLOBALS['TL_HOOKS']['exporter_modifyMediaFilename'] as $callback)
							{
								$objCallback = \System::importStatic($callback[0]);
								$strFixedFilename = $objCallback->$callback[1]($objFile, $key, $strPath, $this);

								if ($strFixedFilename)
								{
									$strTmpFixedFilename = $strTmpFolder . '/' . ltrim($strFixedFilename, '/');
									$objFile->copyTo($strTmpFixedFilename);
									$objFile->path = $strTmpFixedFilename;
								}
							}
						}

						switch ($this->strCompressionType)
						{
							default:
								$objZip->addFile($objFile->path);
								break;
						}
					}
				}
			}
		}

		switch ($this->strCompressionType)
		{
			default:
				$objZip->close();
				break;
		}

		$objTmpFolder = new \Folder($strTmpFolder);
		if (is_dir(TL_ROOT . '/' . $objTmpFolder->path))
			$objTmpFolder->delete();

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
		return 'export-' . $this->strTable . '_' . date('Y-m-d_H-i', time()) . '.' . $this->strCompressionType;
	}
}