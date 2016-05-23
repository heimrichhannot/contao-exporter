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

class XlsExporter extends Exporter
{
	protected $strFileType = EXPORTER_FILE_TYPE_XLS;
	protected $strWriterOutputType = 'Excel5';

	protected $arrHeaderFields = array();
	protected $arrExportFields = array();

	public function __construct($objConfig)
	{
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

}