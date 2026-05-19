<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\BulkPluginUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\BulkThemeUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\EnvironmentPlugins;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\EnvironmentThemes;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\PluginUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\ThemeUpdateData;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\BulkUpdatePluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\BulkUpdateThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\GetEnvironmentPluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\GetEnvironmentThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\UpdatePluginRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\UpdateThemeRequest;

/** @mixin Kinsta */
trait SupportsEnvironmentPluginsThemesEndpoints
{
    public function getEnvironmentPlugins(string $envId): EnvironmentPlugins
    {
        $request = new GetEnvironmentPluginsRequest($envId);

        return $this->send($request)->dto();
    }

    public function updatePlugin(string $envId, PluginUpdateData $data): OperationResponse
    {
        $request = new UpdatePluginRequest($envId, $data);

        return $this->send($request)->dto();
    }

    public function bulkUpdatePlugins(string $envId, BulkPluginUpdateData $data): OperationResponse
    {
        $request = new BulkUpdatePluginsRequest($envId, $data);

        return $this->send($request)->dto();
    }

    public function getEnvironmentThemes(string $envId): EnvironmentThemes
    {
        $request = new GetEnvironmentThemesRequest($envId);

        return $this->send($request)->dto();
    }

    public function updateTheme(string $envId, ThemeUpdateData $data): OperationResponse
    {
        $request = new UpdateThemeRequest($envId, $data);

        return $this->send($request)->dto();
    }

    public function bulkUpdateThemes(string $envId, BulkThemeUpdateData $data): OperationResponse
    {
        $request = new BulkUpdateThemesRequest($envId, $data);

        return $this->send($request)->dto();
    }
}
