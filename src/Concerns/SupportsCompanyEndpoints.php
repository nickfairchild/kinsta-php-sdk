<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Company\PluginThemePayload;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetActivityLogsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetAPIKeysRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyPluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyUsersRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetRegionsRequest;

/** mixin Kinsta */
trait SupportsCompanyEndpoints
{
    public function companyUsers(): array
    {
        $request = new GetCompanyUsersRequest($this->companyId);

        return $this->send($request)->dto();
    }

    public function regions(): array
    {
        $request = new GetRegionsRequest($this->companyId);

        return $this->send($request)->dto();
    }

    public function apiKeys(): array
    {
        $request = new GetAPIKeysRequest($this->companyId);

        return $this->send($request)->dto();
    }

    public function activityLogs(): array
    {
        $request = new GetActivityLogsRequest($this->companyId);

        return $this->send($request)->dto();
    }

    public function companyPlugins(PluginThemePayload $payload)
    {
        $request = new GetCompanyPluginsRequest($this->companyId, $payload);

        return $this->send($request)->dto();
    }

    public function companyThemes(PluginThemePayload $payload)
    {
        $request = new GetCompanyThemesRequest($this->companyId, $payload);

        return $this->send($request)->dto();
    }
}
