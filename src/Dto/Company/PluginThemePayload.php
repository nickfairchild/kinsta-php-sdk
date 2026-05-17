<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\Company;

class PluginThemePayload
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 10,
        public string $search = '',
        public string $status = 'active',
        public string $column = 'updatesAvailable',
        public array $orderBy = ['field' => 'name', 'order' => 'ascend'],
    ) {}
}
