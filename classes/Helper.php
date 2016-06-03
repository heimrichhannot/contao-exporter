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

use HeimrichHannot\Haste\Dca\General;

class Helper
{
	public static function getArchiveName($strTable)
	{
		$strPTable = $GLOBALS['TL_DCA'][$strTable]['config']['ptable'];
		$intPid = \Input::get('id');

		if($strPTable)
		{
			$objInstance = General::getModelInstance($strPTable, $intPid);
			return $objInstance->title;
		}
		else{
			return $strTable;
		}
	}
}