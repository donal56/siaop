<?php

namespace app\components;

use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

class CustomFileInput extends FileInput {

    public function __construct($config = []) {

        $defaultPluginOptions = [
            'required' => true,
            'uploadAsync' => true,
            'minFileCount' => 0,
            'maxFileSize' => 2048, // En Kb, 2MB
            'browseLabel' =>  ' Seleccionar',
            'removeLabel' =>  ' Eliminar',
            'removeTitle' => 'Eliminar archivos seleccionados',
            'uploadLabel' => 'Subir archivos',
            'uploadTitle' => 'Subir archivos',
            'msgNoFilesSelected' => 'No se seleccionaron archivos',
            'msgPlaceholder' => 'Seleccione un archivo...',
            'msgZoomTitle' => 'Previsualizar',
            'msgZoomModalHeading' => 'Previsualización',
            'msgFileRequired' => 'Archivo requerido',
            'msgFileNotFound' => 'Archivo "{name}" no encontrado',
            'msgFileSecured' => 'Restricciones de seguridad no permiten leer el archivo <code>"{name}"</code>',
            'msgFileNotReadable' => 'El archivo "{name}" no puede ser leído',
            'msgFilePreviewAborted' => 'File preview aborted for "{name}"',
            'msgFilePreviewError' => 'An error occurred while reading the file "{name}"',
            'msgInvalidFileName' => 'Invalid or unsupported characters in file name "{name}"',
            'msgFilesTooMany' => 'Limite de archivos <b>({m})</b> excedido. <b>{n}</b> archivo(s) seleccionado(s).',
            'msgTotalFilesTooMany' => 'Limite de archivos <b>({m})</b> excedido. <b>{n}</b> archivo(s) seleccionado(s).',
            'msgSizeTooLarge' => 'El archivo "{name}" (<b>{size} KB</b>) excede el máximo permitido de <b>{maxSize} KB</b>.',
            'msgUploadThreshold' => 'Subiendo...',
            'msgUploadBegin' => 'Preparando archivos...',
            'msgUploadEnd' => 'Listo',
            'msgUploadError' => 'Error al subir archivos',
            'msgDeleteError' => 'Error al eliminar archivos',
            'msgLoading' => 'Subiento archivo {index} de {files}',
            'msgProgress' => 'Subiento archivo {index} de {files} - {name} - {percent}% completado',
            'msgSelected' => '{n} archivo(s) seleccionado(s)',
            'msgProcessing' => 'Procesando...',
            'msgFoldersNotAllowed' => 'Solo se soportan archivos. Se salto la carga de {n} carpeta(s)',
            'msgPendingTime' => '{time} restante',
            'msgCalculatingTime' => 'Calculando tiempo restante',
            'msgAjaxError' => 'Algo ocurrio mal durante {operation}. Por favor, inténtelo de nuevo más tarde.',
            'msgAjaxProgressError' => '{operation} fallo',
            'msgDuplicateFile' => 'El archivo "{name}" de tamaño "{size} KB" ya habia sido cargado antes. Saltando duplicado.',
            'ajaxOperations' => [
                'deleteThumb' => 'la eliminación de un archivo',
                'uploadThumb' => 'la subida de un archivo',
            ],
            'dropZoneTitle' => 'Arrastre archivos en esta zona o haga click aquí para seleccionar archivos',
            'progressClass' => 'progress-bar progress-bar-success progress-bar-striped active progress-bar-fix-style',
            'progressCompleteClass' => 'progress-bar progress-bar-success progress-bar-fix-style',
            'progressErrorClass' => 'progress-bar progress-bar-danger progress-bar-fix-style',
            'layoutTemplates' => [
                'preview' => '<div class="file-preview {class}">' .
                            '    <div class="close fileinput-remove mt-2" style="top: 6px;right: 5px">×</div>' .
                            '    <div class="{dropClass}" onclick= "
                            this.parentElement.parentElement.parentElement.parentElement.parentElement.querySelectorAll(`.file-preview-thumbnails > div`).length == 0 && this.parentElement.parentElement.parentElement.parentElement.querySelector(`input[type=file][data-krajee-fileinput]`).click()">' .
                            '    <div class="file-preview-thumbnails">' .
                            '    </div>' .
                            '    <div class="clearfix"></div>' .
                            '    <div class="file-preview-status text-center text-success" style= "padding-left: 30px"></div>' .
                            '    <div class="kv-fileinput-error" style= "padding-left: 30px"></div>' .
                            '    </div>' .
                            '</div>',
                'main2' => '{preview} <div class="kv-upload-progress hide"></div> {remove} {cancel} {upload} {browse}',
                'progress' => '<div class="progress">' .
                            '    <div class="progress-bar progress-bar-success progress-bar-striped text-center" role="progressbar" aria-valuenow="{percent}" aria-valuemin="0" aria-valuemax="100" style="width:{percent}%; font-size: 10px">' .
                            '        {status}' .
                            '     </div>' .
                            '</div>' .
                            '{stats}',
                'main1' => '{preview}' .
                            '<div class="kv-upload-progress kv-hidden"></div>' .
                            '<div class="clearfix"></div>' .
                            '<div class="file-caption {class}">' .
                            '   <span class="file-caption-icon"></span>' .
                            '   <div class="input-group">{caption}' .
                            '       <div class="input-group-btn input-group-append">' .
                            '           {remove} {cancel} {pause} {upload} {browse}' .
                            '       </div>' .
                            '   </div>' .
                            '</div>',
                'actions' => '<div class="file-actions">' .
                            '    <div class="file-footer-buttons">' .
                            '        {upload} {download} {delete} {zoom} {other}' .
                            '    </div>' .
                            '    {drag}' .
                            '    <div class="file-upload-indicator" title="{indicatorTitle}"></div>' .
                            '    <div class="clearfix"></div>' .
                            '</div>',
            ],
            'previewSettings' => [
                'image' => [ 
                    'width' => "auto", 
                    'height' => "auto", 
                    'max-width' => "100%", 
                    'max-height' => "100%" 
                ],
                'html' => [ 
                    'width' => "213px", 
                    'height' => "160px" 
                ],
                'text' => [ 
                    'width' => "213px", 
                    'height' => "160px" 
                ],
                'office' => [ 
                    'width' => "213px", 
                    'height' => "160px" 
                ],
                'gdocs' => [ 
                    'width' => "213px", 
                    'height' => "160px" 
                ],
                'video' => [ 
                    'width' => "213px", 
                    'height' => "160px" 
                ],
                'audio' => [ 
                    'width' => "100%", 
                    'height' => "30px" 
                ],
                'flash' => [ 
                    'width' => "213px", 
                    'height' => "160px" 
                ],
                'object' => [ 
                    'width' => "213px", 
                    'height' => "160px" 
                ],
                'pdf' => [
                    'width' => "235px", 
                    'height' => "160px" 
                ],
                'other' => [ 
                    'width' => "213px", 
                    'height' => "160px" 
                ]
            ],
            'fileActionSettings' => [
                'removeTitle' => 'Remover archivo',
                'uploadTitle' => 'Subir archivo',
                'uploadRetryTitle' => 'Reintentar subida',
                'downloadTitle' => 'Descargar archivo',
                'zoomTitle' => 'Previsualización',
                'dragTitle' => 'Mover',
                'indicatorNewTitle' => 'Aun no subido',
                'indicatorSuccessTitle' => 'Subido',
                'indicatorErrorTitle' => 'Error al subir',
                'indicatorLoadingTitle' => 'Subiendo...',
                'indicatorPausedTitle' => 'Subida pausada',
                'uploadRetryIcon' => '<i class="fas fa-history"></i>',
                'showDownload' => true,
                'downloadIcon' => '<i class="fas fa-cloud-download-alt"></i>'
            ]
        ];

        $config['pluginOptions'] = ArrayHelper::merge($defaultPluginOptions, $config['pluginOptions']);
        $config['autoOrientImages'] = true;

        if($config['pluginOptions']['minFileCount'] == 0) {
            $config['pluginOptions']['required'] = false;
        }

        parent::__construct($config);
    }
}