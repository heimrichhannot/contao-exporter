<?php

namespace HeimrichHannot\Exporter;

class ModuleExporter
{

	public static function export()
	{


		$strGlobalOperationKey = \Input::get('key');
		$strTable = \Input::get('table');

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
				case EXPORTER_FILE_TYPE_XLS:
					$objExporter = new XlsExporter($objConfig);
					break;
				case EXPORTER_FILE_TYPE_MEDIA:
					$objExporter = new MediaExporter($objConfig);
					break;
			}

			if ($objExporter)
				$objExporter->export();

			die();
		}
	}

	public static function getGlobalOperation($strName, $strLabel = '', $strIcon = '')
	{
		$arrOperation = array
		(
			'label'      => &$strLabel,
			'href'       => 'key=' . $strName,
			'class'      => 'header_' . $strName . '_entities',
			'icon'       => $strIcon,
			'attributes' => 'onclick="Backend.getScrollOffset()"'
		);

		return $arrOperation;
	}

	public static function getBackendModule()
	{
		return array('HeimrichHannot\Exporter\ModuleExporter', 'export');
	}

}
