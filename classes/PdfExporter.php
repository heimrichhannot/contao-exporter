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

use HeimrichHannot\Haste\Pdf\PdfTemplate;
use HeimrichHannot\Haste\Util\Files;

class PdfExporter extends Exporter
{
	protected $strPdfTemplate = 'exporter_pdf_default_item';

	protected function exportToDownload($intId)
	{
		$arrExportFields = array();

		$arrDca = $GLOBALS['TL_DCA'][$this->linkedTable];

		foreach (deserialize($this->tableFieldsForExport, true) as $strField)
		{
			if (strpos($strField, EXPORTER_RAW_FIELD_SUFFIX) !== false)
				$arrExportFields[] = str_replace(EXPORTER_RAW_FIELD_SUFFIX, '', $strField) . ' AS ' . $strField;
			else
				$arrExportFields[] = $strField;
		}

		$strQuery = $this->buildSqlQuery($intId, $arrExportFields, $arrDca);
		$objDbResult = \Database::getInstance()->prepare($strQuery)->execute();

		if (!$objDbResult->numRows > 0)
		{
			if ($this->blnJoin)
			{
				$this->setJoinReset(true);
				$strQuery = $this->buildSqlQuery($intId, $arrExportFields, $arrDca);
				$objDbResult = \Database::getInstance()->prepare($strQuery)->execute();

				if (!$objDbResult->numRows > 0)
					return;
			}
			else
			{
				return;
			}
		}

		if(!$this->objConfig->current()->addJoinTables)
		{
			$arrResult = $objDbResult->row();
		}
		else
		{
			while($objDbResult->next())
			{
				$arrResult[] = $objDbResult->row();
			}
		}

		// Hooks
		if (isset($GLOBALS['TL_HOOKS']['modifyPdfExporterResults']) && is_array($GLOBALS['TL_HOOKS']['modifyPdfExporterResults']))
		{
			foreach ($GLOBALS['TL_HOOKS']['modifyPdfExporterResults'] as $callback)
			{
				$this->import($callback[0]);
				$arrResult = $this->{$callback[0]}->{$callback[1]}($arrResult, $this->objConfig, $this->strExportType, $this->linkedTable, $arrExportFields);
			}
		}

		$objPdf = new PdfTemplate();

		if ($this->objConfig->pdfBackground)
		{
			$objPdf->addTemplatePdf(Files::getPathFromUuid($this->objConfig->pdfBackground));
		}
		if ($this->objConfig->pdfTemplate)
		{
			$this->strPdfTemplate = $this->objConfig->pdfTemplate;
		}

		$this->setTemplate($this->strPdfTemplate);
		$objPdf->writeHtml($this->parseTemplate($arrResult));
		$objPdf->sendToBrowser($this->strFilename, 'D');
	}

	protected function buildSqlQuery($intId, $arrExportFields, $arrDca)
	{
		$strQuery = '';

		if ($this->objConfig->current()->addJoinTables)
			$this->setJoin(true);

		if ($this->blnResetJoin)
		{
			$arrExportFields = $this->resetExportFields($arrExportFields);
			$this->setJoin(false);
		}

		switch ($this->strExportType)
		{
			case 'item':
				if ($intId != '') {
					if($this->blnJoin)
					{
						$strQuery = $this->buildJoinQuery($intId, $arrExportFields);
					}
					else{
						$strQuery = 'SELECT ' . implode(',', $arrExportFields) . ' FROM ' . $this->linkedTable;

						if (TL_MODE == 'BE')
						{
							$strQuery .= $this->buildWhereClause($intId);
						}
					}
				}
				break;

			case 'list':
			default:
				if($this->blnJoin)
				{
					$strQuery = $this->buildJoinQuery($intId, $arrExportFields);
				}
				else{
					$strQuery = 'SELECT ' . implode(',', $arrExportFields) . ' FROM ' . $this->linkedTable;

					if (TL_MODE == 'BE')
					{
						$strAct = \Input::get('act');
						$intPid = \Input::get('id');
						$strWhere = '';

						if($this->whereClause)
							$strWhere = ' AND ' . html_entity_decode($this->whereClause);

						if ($intPid && !$strAct && is_array($arrDca['fields']) && $arrDca['config']['ptable'])
						{
							$strQuery .= ' WHERE pid = ' . $intPid . $strWhere;
						}
					}
				}
				break;
		}

		if($this->orderBy)
			$strQuery .= ' ORDER BY ' . $this->orderBy;

		return $strQuery;
	}

	protected function buildJoinQuery($intId, $arrExportFields)
	{
		$arrJoinTables = $this->getJoinTables();

		$strQuery = 'SELECT ' . implode(',', $arrExportFields) . ' FROM ' . $this->linkedTable;

		foreach($arrJoinTables as $joinT)
		{
			$strQuery .= ' INNER JOIN ' . $joinT['title'] . ' ON ' . $joinT['condition'];
		}

		return $strQuery . $this->buildWhereClause($intId);
	}

	protected function buildWhereClause($intId)
	{
		$strWhere = '';

		if ($intId)
		{
			if($this->whereClause)
			{
				$strWhere .= ' WHERE ' . html_entity_decode($this->whereClause) . ' AND ' . $this->linkedTable . '.id=' . $intId;
			}
			else
			{
				$strWhere .= ' WHERE ' . $this->linkedTable . '.id=' . $intId;
			}
		}
		else
		{
			if($this->whereClause)
				$strWhere .= ' WHERE ' . html_entity_decode($this->whereClause);
		}

		return $strWhere;
	}

	protected function resetExportFields($arrExportFields)
	{
		$arrFields = array();

		foreach ($arrExportFields as $strField)
		{
			if (strpos($strField, $this->linkedTable . '.') !== false)
				$arrFields[] = $strField;
		}

		return $arrFields;
	}
}