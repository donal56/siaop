<?php

namespace app\components;

use app\models\Parametros;
use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use yii\helpers\ArrayHelper;

class ExcelHelper {

    /**
     * @param String titulo - Nombre del archivo generado
     * @param String subtitulo - Subtítulo del archivo generado
     * @param Array modelsBySheet - Arreglo de arreglos de modelos ActiveRecord. Por cada elemento del arreglo principal se creara una hoja de trabajo que describira el arreglo de modelos provisto. La llave es el nombre de la hoja y el valor los datos.
     * @param Array propiedades - Propiedades a exportar. Las llaves serán las cabeceras y los valores lo imprimido en la celda. El valor puede ser una función que toma el modelo como parámetro.
     */
    public static function export($titulo, $subtitulo, $modelsBySheet, $propiedades = []) {

        $doc = new Spreadsheet();
        $doc->getProperties()
            ->setCreator("Brain Mexico")
            ->setLastModifiedBy("Brain Mexico")
            ->setTitle($titulo)
            ->setSubject(empty($subtitulo) ? '' : $subtitulo)
            ->setDescription('Archivo autogenerado por BrainMX el ' . date('dd-MM-YYYY'));

        $first = true;

        // Crear hojas
        foreach ($modelsBySheet as $sheetName => $models) {

            if($first) {
                $sheet = $doc->getActiveSheet();
                $sheet->setTitle($sheetName);
                $sheet->setCodeName($sheetName);
                $first = false;
            }
            else {
                $sheet = $doc->createSheet();
                $sheet->setTitle($sheetName);
                $sheet->setCodeName($sheetName);
                $doc->setActiveSheetIndexByName($sheet->getTitle());
            }

            $keys = array_keys($propiedades);
            // $dateKeys = array_filter($keys, fn($value, $key) => str_contains(strtolower($value), 'fecha'), ARRAY_FILTER_USE_BOTH);

            $sheet->setCellValue('A1', 'BRAIN MEXICO');
            $sheet->setCellValue('A2', mb_strtoupper($titulo));
            $sheet->setCellValue('A3', mb_strtoupper($subtitulo));
            $sheet->getStyle('A1:A3')->getFont()->setBold(true);
            $sheet->getStyle('A1:A3')->getFont()->setSize(14);
            $sheet->getStyle('A1:A3')->getAlignment()->setWrapText(true);
            $sheet->getRowDimension('3')->setRowHeight(25);
            $sheet->getRowDimension('4')->setRowHeight(25);

            // Logo de Brain
            $logo = new Drawing();
            $logo->setName('Logo');
            $logo->setDescription('Logo');
            $logo->setPath(Yii::getAlias('@webroot/img/brain-inline.png'));
            $logo->setHeight(85);
            $logo->setCoordinates('C1');
            $logo->setWorksheet($sheet);

            // Logo del socio comercial
            $parametroLogoSocio = Parametros::leerParametroDeLaEmpresa("LOGO");

            if(!empty($parametroLogoSocio)) {
                $logo2 = new Drawing();
                $logo2->setName('Logo del socio comercial');
                $logo2->setDescription('Logo del socio comercial');
                $logo2->setPath(Yii::getAlias('@webroot/' . $parametroLogoSocio));
                $logo2->setHeight(85);
                $logo2->setCoordinates('H1');
                $logo2->setWorksheet($sheet);
            }
            
            // Agregar datos
            $doc->setActiveSheetIndex(0);
            $sheet->fromArray($keys, null, 'A5');

            $i = 6;
            foreach($models as $model) {
                $row = ArrayHelper::toArray($model, [$model::class => $propiedades], true);
                $sheet->fromArray($row, null, 'A' . $i);
                
                /*
                TODO: Detectar fechas y foramtearlas correctamente
                foreach ($dateKeys as $key => $value) {
                    $sheet->getCellByColumnAndRow($key + 1, $i, false)
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                }
                */

                $i++;
            }
        
            $last_column = $sheet->getHighestColumn();
            $last_row = $sheet->getHighestRow();
        
            // Autotamaño de columnas
            foreach($sheet->getColumnIterator() as $column) {
                $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        
            // Congelar encabezados
            $sheet->freezePane('A6');

            // Estilo de cabeceras
            $sheet->getStyle('A5:' . $last_column . '5')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => 'FFFFFF'
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => '0070C0'
                    ]
                ]
            ]);

            //Wrap en todas las columnas
            # $sheet->getStyle('A1:A3')->getAlignment()->setWrapText(true);
        }

        $doc->setActiveSheetIndex(0);

        // Mandar a descarga
        ob_clean();
        $writer = new Xlsx($doc);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $titulo . '.xlsx"');
        $writer->save('php://output');
        exit();
    }
}