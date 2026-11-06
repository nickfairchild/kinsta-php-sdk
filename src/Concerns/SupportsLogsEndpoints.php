<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsResult;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRules;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRulesQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRulesUpdateData;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\GetLogsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\GetRedirectRulesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\UpdateRedirectRulesRequest;

/** @mixin Kinsta */
trait SupportsLogsEndpoints
{
    public function getLogs(string $envId, LogsQuery $query): LogsResult
    {
        $request = new GetLogsRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function getRedirectRules(string $envId, ?RedirectRulesQuery $query = null): RedirectRules
    {
        $request = new GetRedirectRulesRequest($envId, $query);

        return $this->send($request)->dto();
    }

    public function updateRedirectRules(string $envId, RedirectRulesUpdateData $data): bool
    {
        $request = new UpdateRedirectRulesRequest($envId, $data);

        return $this->send($request)->dto();
    }
}
