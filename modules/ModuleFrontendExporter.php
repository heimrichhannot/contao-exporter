<?php

namespace HeimrichHannot\Exporter;

class ModuleFrontendExporter extends \Module
{
	protected $strTemplate = 'mod_frontend_export';

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['frontendExporter'][0] . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}

	protected function compile()
	{
		$intMemberId = $this->getMemberId();

		if (\Input::post('export'))
		{
			$objConfig = ExporterModel::findById($this->exporterConfig);

			if ($objConfig == null)
				return;

			switch(\Input::post('export'))
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
				$objExporter->export($this->exporterExportType, $intMemberId);
		}

		$this->Template->action = '';
		$this->Template->method = 'POST';
		$this->Template->noRights = $intMemberId ? false : true;

		$this->Template->btnLabel = $this->exporterBtnLabel;
	}

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

	protected function getMemberId()
	{
		$intMemberId = null;

		if (TL_MODE == 'FE')
		{
			$this->import('FrontendUser', 'Member');
			if (FE_USER_LOGGED_IN)
			{
				$intMemberId = $this->Member->id;

				$arrMemberGroups = $this->Member->groups;
				$arrRequiredGrous = deserialize($this->exporterUseIdGroups, true);
				$arrIntersect = array_intersect($arrMemberGroups, $arrRequiredGrous);

				if ($this->exporterExportType == 'item' && $this->exporterUseIdFromUrl &&
					is_numeric(\Input::get('id')) && \Input::get('id') != $this->Member->id)
				{
					$intMemberId = \Input::get('id');

					if ($this->exporterUseIdGroups != null && empty($arrIntersect))
					{
						$intMemberId = null;
					}
				}

			}
		}

		return $intMemberId;
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
