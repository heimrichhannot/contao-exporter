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
use HeimrichHannot\Haste\Util\FormSubmission;

abstract class PhpExcelExporter extends Exporter
{
    protected $arrHeaderFields = array();
    protected $arrExportFields = array();

    public function __construct($objConfig)
    {
        parent::__construct($objConfig);

        $this->objPhpExcel = new \PHPExcel();
    }

    public function export($objEntity = null, array $arrFields = array())
    {
        $this->setHeaderFields();

        parent::export($objEntity, $arrFields);
    }

    protected function doExport($objEntity = null, array $arrFields = array())
    {
        switch ($this->type)
        {
            case Exporter::TYPE_LIST:
                break;
            case Exporter::TYPE_ITEM:
                $objDbResult = $this->getEntities();
                $arrDca          = $GLOBALS['TL_DCA'][$this->linkedTable];

                if (!$objDbResult->numRows > 0)
                {
                    return;
                }

                $intCol = 0;
                $intRow = 1;

                // header
                if ($this->objConfig->addHeaderToExportTable)
                {
                    foreach ($this->arrHeaderFields as $varValue)
                    {
                        $this->objPhpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($intCol, $intRow, $varValue);
                        $this->processHeaderRow($intCol);
                        $intCol++;
                    }
                    $intRow++;
                }

                // body
                while ($objDbResult->next())
                {
                    $arrRow = $objDbResult->row();
                    $intCol = 0;
                    foreach ($arrRow as $key => $varValue)
                    {
                        $objDc               = new \DC_Table($this->linkedTable);
                        $objDc->activeRecord = $objDbResult;
                        $objDc->id           = $objDbResult->id;
                        $varValue            = $this->localizeFields ? FormSubmission::prepareSpecialValueForPrint(
                            $varValue,
                            $arrDca['fields'][$key],
                            $this->linkedTable,
                            $objDc
                        ) : $varValue;

                        if (is_array($varValue))
                        {
                            $varValue = Arrays::flattenArray($varValue);
                        }

                        $this->objPhpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(
                            $intCol,
                            $intRow,
                            html_entity_decode($varValue)
                        );

                        $this->objPhpExcel->getActiveSheet()->getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex($intCol))->setAutoSize(true);
                        $this->processBodyRow($intCol);
                        $intCol++;
                    }
                    $this->objPhpExcel->getActiveSheet()->getRowDimension($intRow)->setRowHeight(-1);
                    $intRow++;
                }

                $this->objPhpExcel->setActiveSheetIndex(0);
                $this->objPhpExcel->getActiveSheet()->setTitle('Export');

                return $this->objPhpExcel;
                break;
        }

        return false;
    }

    public function exportToDownload($objResult)
    {
        $strTmpFile = 'system/tmp/' . $this->strFilename;

        // send file to browser
        $objWriter = \PHPExcel_IOFactory::createWriter($objResult, $this->strWriterOutputType);
        $this->updateWriter($objWriter);
        $objWriter->save(TL_ROOT . '/' . $strTmpFile);
        $objFile = new \File($strTmpFile);
        $objFile->sendToBrowser();
    }

    public function exportToFile($objResult)
    {

    }
}