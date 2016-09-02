<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) Heimrich & Hannot GmbH
 * @package frontendedit
 * @author Dennis Patzer
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrDca = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Palettes
 */
$arrDca['palettes']['frontendExporter'] = '{title_legend},name,headline,type;' .
	'{exporter_legend},exporterConfig,exporterExportType,exporterBtnLabel;' .
	'{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$arrDca['palettes']['__selector__'][] = 'exporterExportType';
$arrDca['palettes']['__selector__'][] = 'exporterUseIdFromUrl';

$arrDca['subpalettes']['exporterExportType_item'] = 'exporterUseIdFromUrl';
$arrDca['subpalettes']['exporterUseIdFromUrl'] = 'exporterUseIdGroups';

/**
 * Fields
 */
$arrFields = array(
	'exporterConfig' => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['exporterConfig'],
		'inputType'        => 'select',
		'options_callback' => array('tl_module_frontend_exporter', 'getExportConfigs'),
		'eval'             => array(
			'chosen'             => true,
			'includeBlankOption' => true,
			'mandatory'          => true,
			'tl_class'           => 'w50',
		),
		'sql'              => "int(10) unsigned NOT NULL default '0'"
	),
	'exporterExportType' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['exporterExportType'],
		'inputType' => 'select',
		'options'   => array('list', 'item'),
		'reference' => &$GLOBALS['TL_LANG']['tl_module']['exporterExportType'],
		'eval'      => array(
			'mandatory'      => true,
			'submitOnChange' => true,
			'tl_class'       => 'w50'
		),
		'sql'       => "varchar(4) NOT NULL default ''"
	),
	'exporterBtnLabel' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['exporterBtnLabel'],
		'inputType' => 'text',
		'eval'      => array(
			'maxlength' => 120,
			'tl_class'  => 'w50 clr'
		),
		'sql'       => "varchar(120) NOT NULL default ''",
	),
	'exporterUseIdFromUrl' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['exporterUseIdFromUrl'],
		'inputType' => 'checkbox',
		'eval'      => array(
			'submitOnChange' => true,
			'tl_class'       => 'w50'
		),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'exporterUseIdGroups' => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['exporterUseIdGroups'],
		'inputType'        => 'select',
		'options_callback' => array('tl_module_frontend_exporter', 'getMemberGroups'),
		'eval'             => array(
			'chosen'             => true,
			'includeBlankOption' => true,
			'multiple'           => true,
			'tl_class'           => 'w50'
		),
		'sql'              => "blob NULL"
	),
);

$arrDca['fields'] += $arrFields;

class tl_module_frontend_exporter {

	public static function getExportConfigs()
	{
		$arrOptions = array();
		$arrConfigs = \HeimrichHannot\Exporter\ExporterModel::findAll();

		if ($arrConfigs !== null)
		{
			foreach ($arrConfigs as $objConfig)
			{
				$arrOptions[$objConfig->id] = $objConfig->title;
			}
		}

		return $arrOptions;
	}

	public static function getMemberGroups()
	{
		$arrOptions = array();
		$arrMemberGroups = \MemberGroupModel::findAllActive();

		if ($arrMemberGroups !== null)
		{
			foreach ($arrMemberGroups as $objMemberGroup)
			{
				$arrOptions[$objMemberGroup->id] = $objMemberGroup->name;
			}
		}

		return $arrOptions;
	}
}