<?php

namespace HeimrichHannot\Exporter;

class ModuleExporter
{

	public static function export($objDc)
	{
		$strExportType = \Input::get('exportType') ? : 'list';
		$strGlobalOperationKey = \Input::get('key');
		$intId = \Input::get('id') ? : '';
		$strTable = \Input::get('table') ? : $objDc->table;

		if (!$strGlobalOperationKey || !$strTable)
			return;

		if (($objConfig = ExporterModel::findByKeyAndTable($strGlobalOperationKey, $strTable)) === null)
		{
			if (empty($_SESSION['TL_ERROR']))
			{
				\Message::addError($GLOBALS['TL_LANG']['MSC']['exporter']['noConfigFound']);
				\Controller::redirect($_SERVER['HTTP_REFERER']);
			}
		}
		else
		{
			$objExporter = null;

			switch($objConfig->fileType)
			{
				case EXPORTER_FILE_TYPE_CSV:
					$objExporter = new CsvExporter($objConfig);
					break;
				case EXPORTER_FILE_TYPE_MEDIA:
					$objExporter = new MediaExporter($objConfig);
					break;
				case EXPORTER_FILE_TYPE_PDF:
					$objExporter = new PdfExporter($objConfig);
					break;
				case EXPORTER_FILE_TYPE_XLS:
					$objExporter = new XlsExporter($objConfig);
					break;
			}

			if ($objExporter)
				$objExporter->export($strExportType, $intId);

			die();
		}
	}

	public static function getGlobalOperation($strName, $strLabel = '', $strIcon = '')
	{
		$arrOperation = array
		(
			'label'      => &$strLabel,
			'href'       => 'exportType=list&key=' . $strName,
			'class'      => 'header_' . $strName . '_entities',
			'icon'       => $strIcon,
			'attributes' => 'onclick="Backend.getScrollOffset()"'
		);

		return $arrOperation;
	}

	public static function getOperation($strName, $strLabel = '', $strIcon = '')
	{
		$arrOperation = array
		(
			'label'      => &$strLabel,
			'href'       => 'exportType=item&key=' . $strName,
			'icon'       => $strIcon,
		);

		return $arrOperation;
	}

	public static function getBackendModule()
	{
		return array('HeimrichHannot\Exporter\ModuleExporter', 'export');
	}

}
