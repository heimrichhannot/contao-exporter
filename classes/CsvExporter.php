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
use HeimrichHannot\Haste\Dca\General;
use HeimrichHannot\Haste\Util\Files;

class CsvExporter extends Exporter
{
	protected $strFileType = EXPORTER_FILE_TYPE_CSV;
	protected $strWriterOutputType = 'CSV';

	protected $arrHeaderFields = array();
	protected $arrExportFields = array();

	public function __construct($objConfig) {
		parent::__construct($objConfig);

		$this->objPhpExcel = new \PHPExcel();
	}

	public function export()
	{
		if ($this->objConfig->addHeaderToExportTable)
		{
			$this->setHeaderFields();
		}

		parent::export();
	}

	public function processHeaderRow($intCol)
	{
		$this->objPhpExcel->getActiveSheet()->getStyle(\PHPExcel_Cell::stringFromColumnIndex($intCol))->getAlignment()->setWrapText(true);
	}

	public function processBodyRow($intCol)
	{
		$this->objPhpExcel->getActiveSheet()->getStyle(\PHPExcel_Cell::stringFromColumnIndex($intCol))->getAlignment()->setWrapText(true);
	}

	public function updateWriter($objWriter)
	{
		$objWriter->setDelimiter($this->delimiter ?: ',')->setEnclosure($this->enclosure ?: '"')->setSheetIndex(0);
	}
}