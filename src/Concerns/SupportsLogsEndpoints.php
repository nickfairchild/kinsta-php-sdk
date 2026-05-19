<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsResult;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRules;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
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

    public function getRedirectRules(string $envId): RedirectRules
    {
        $request = new GetRedirectRulesRequest($envId);

        return $this->send($request)->dto();
    }

    public function updateRedirectRules(string $envId, array $rules): OperationResponse
    {
        $request = new UpdateRedirectRulesRequest($envId, $rules);

        return $this->send($request)->dto();
    }
}
