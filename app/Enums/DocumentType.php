<?php

namespace App\Enums;

enum DocumentType:string
{
    case CEDULA_CIUDADANIA = 'cedula de ciudadanía';
    case TARJETA_IDENTIDAD = 'tarjeta de identidad';
    case CEDULA_EXTRANJERIA = 'cedula de extranjeria';
    case PASAPORTE = 'pasaporte';
}
