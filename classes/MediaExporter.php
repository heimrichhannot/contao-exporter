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
use HeimrichHannot\Haste\Util\FormSubmission;

class MediaExporter
{
	protected function exportToDownload()
	{
		$strTmpFile = 'system/tmp/' . $this->strFilename;
		$strTmpFolder = str_replace('.' . $this->compressionType, '', $strTmpFile);
		$arrExportFields = array();
		$arrDca = $GLOBALS['TL_DCA'][$this->linkedTable]['fields'];

		foreach (deserialize($this->tableFieldsForExport, true) as $strField)
		{
			if (strpos($strField, EXPORTER_RAW_FIELD_SUFFIX) !== false)
				$arrExportFields[] = str_replace(EXPORTER_RAW_FIELD_SUFFIX, '', $strField) . ' AS ' . $strField;
			else
				$arrExportFields[] = $strField;
		}

		$objDbResult = \Database::getInstance()->prepare(
				"SELECT " . implode(',', $arrExportFields) .
				" FROM " . $this->linkedTable
		)->execute();

		if (!$objDbResult->numRows > 0)
			return;

		switch ($this->compressionType)
		{
			default:
				$objZip = new ZipWriter($strTmpFile);
				break;
		}

		// write files
		while ($objDbResult->next())
		{
			$arrRow = $objDbResult->row();

			foreach ($arrRow as $key => $varValue)
			{
				$objDc = new DC_Table($this->linkedTable);
				$objDc->activeRecord = $objDbResult;
				$varValue = FormSubmission::prepareSpecialValueForPrint($varValue, $arrDca['fields'][$key], $this->linkedTable, $objDc);

				if (!is_array($varValue))
					$varValue = array($varValue);

				foreach ($varValue as $strPath)
				{
					if ($strPath && ($objFile = new \File($strPath, true)) !== null && $objFile->exists())
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

						switch ($this->compressionType)
						{
							default:
								$objZip->addFile($objFile->path);
								break;
						}
					}
				}
			}
		}

		switch ($this->compressionType)
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

	protected function buildFileName()
	{
		return 'export-' . $this->linkedTable . '_' . date('Y-m-d_H-i', time()) . '.' . $this->compressionType;
	}
}