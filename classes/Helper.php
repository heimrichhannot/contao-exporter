<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @package ${CARET}
 * @author  Martin Kunitzsch <m.kunitzsch@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Exporter;


use HeimrichHannot\Haste\Dca\General;

class Helper
{
    public static function getArchiveName($strTable)
    {
        $strPTable = $GLOBALS['TL_DCA'][$strTable]['config']['ptable'];
        $intPid    = \Input::get('id');
        if ($strPTable)
        {
            $objInstance = General::getModelInstance($strPTable, $intPid);

            return $objInstance->title;
        }
        else
        {
            return $strTable;
        }
    }

    public static function getJoinTables($intExporter)
    {
        $objJoinTables = \HeimrichHannot\FieldPalette\FieldPaletteModel::findPublishedByPidAndTableAndField(
            $intExporter,
            'tl_exporter',
            'joinTables'
        );

        if ($objJoinTables !== null)
        {
            return $objJoinTables->fetchEach('joinTable');
        }

        return array();
    }

    public static function getJoinTablesAndConditions($intExporter)
    {
        $arrTables = array();

        $objJoins = \HeimrichHannot\FieldPalette\FieldPaletteModel::findPublishedByPidAndTableAndField(
            $intExporter,
            'tl_exporter',
            'joinTables'
        );

        while ($objJoins->next())
        {
            $arrTables[] = array(
                'table'     => $objJoins->joinTable,
                'condition' => $objJoins->joinCondition,
            );
        }

        return $arrTables;
    }
}