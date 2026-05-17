<?php

arch('debug functions are not used')
    ->expect(['dd', 'dump', 'var_dump', 'print_r', 'var_export', 'die', 'exit'])
    ->not->toBeUsed();

arch('Enums')
    ->expect('Nickfairchild\KinstaPhpSdk\Enums')
    ->toBeEnums();

arch('Exceptions')
    ->expect('Nickfairchild\KinstaPhpSdk\Exceptions')
    ->toExtend(Exception::class)
    ->toHaveSuffix('Exception');

arch('Requests')
    ->expect('Nickfairchild\KinstaPhpSdk\Requests')
    ->toExtend('Saloon\Http\Request');

arch('Concerns')
    ->expect('Nickfairchild\KinstaPhpSdk\Concerns')
    ->toBeTraits()
    ->toHavePrefix('Supports');
