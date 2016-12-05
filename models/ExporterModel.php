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

class ExporterModel extends \Model
{

    protected static $strTable = 'tl_exporter';

    public static function findByKeyAndTable($strKey, $strTable, array $arrOptions = array())
    {
        $t = static::$strTable;

        $arrColumns[] = "($t.globalOperationKey='" . $strKey . "')";
        $arrColumns[] = "($t.linkedTable='" . $strTable . "')";

        if (TL_MODE == 'BE' && ($intPid = \Input::get('id')) && !\Input::get('act'))
        {
            $arrColumns[] = "($t.restrictToPids REGEXP '\"$intPid\"' OR $t.restrictToPids IS NULL OR $t.restrictToPids = '' OR $t.restrictToPids = 'a:0:{}')";
        }

        return static::findOneBy($arrColumns, null, $arrOptions);
    }

}
