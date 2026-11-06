<?php

namespace Nickfairchild\KinstaPhpSdk\Enums;

enum ForceHttpsType: string
{
    case ENABLED_FOR_ALL = 'ENABLED_FOR_ALL';
    case ENABLED_REDIRECT_TO_PRIMARY = 'ENABLED_REDIRECT_TO_PRIMARY';
    case DISABLED = 'DISABLED';
}
