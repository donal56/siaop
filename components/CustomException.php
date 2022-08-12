<?php

namespace app\components;

use Exception;
use Throwable;

class CustomException extends Exception {

    /**
     * Códigos de error utilizados:
     *  - 56001: Modelo no encontrado o no existe
     */
    public function __construct($message, $code = 56000, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}