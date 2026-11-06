<?php

namespace Nickfairchild\KinstaPhpSdk\Enums;

enum ImageOptimizationType: string
{
    // The spec lists this value as an unquoted YAML `off`. OpenAPI 3.1 uses
    // YAML 1.2, where that is the string "off"; YAML 1.1 parsers read it as
    // false. Sending "off" is unconfirmed against the live API.
    case OFF = 'off';
    case LOSSY = 'lossy';
    case LOSSLESS = 'lossless';
}
