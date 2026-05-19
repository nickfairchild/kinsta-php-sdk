<?php

use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\BulkPluginUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\BulkThemeUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\PluginUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\ThemeUpdateData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\BulkUpdatePluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\BulkUpdateThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\GetEnvironmentPluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\GetEnvironmentThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\UpdatePluginRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes\UpdateThemeRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get environment plugins', function () {
    MockClient::global([
        GetEnvironmentPluginsRequest::class => MockResponse::make([
            'plugins' => [
                ['slug' => 'akismet', 'version' => '5.3'],
                ['slug' => 'hello-dolly', 'version' => '1.7.2'],
            ],
        ]),
    ]);

    $response = $this->kinsta->getEnvironmentPlugins('env-123');

    expect($response->plugins)->toBeArray()->toHaveCount(2);
});

it('can update a plugin', function () {
    MockClient::global([
        UpdatePluginRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'update-plugin-123',
        ]),
    ]);

    $data = new PluginUpdateData(slug: 'akismet', version: '5.4');

    $response = $this->kinsta->updatePlugin('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('update-plugin-123');
});

it('can bulk update plugins', function () {
    MockClient::global([
        BulkUpdatePluginsRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'bulk-update-plugins-123',
        ]),
    ]);

    $data = new BulkPluginUpdateData(pluginSlugs: ['akismet', 'hello-dolly']);

    $response = $this->kinsta->bulkUpdatePlugins('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('bulk-update-plugins-123');
});

it('can get environment themes', function () {
    MockClient::global([
        GetEnvironmentThemesRequest::class => MockResponse::make([
            'themes' => [
                ['slug' => 'twentytwentyfour', 'version' => '1.0'],
                ['slug' => 'twentytwentythree', 'version' => '1.2'],
            ],
        ]),
    ]);

    $response = $this->kinsta->getEnvironmentThemes('env-123');

    expect($response->themes)->toBeArray()->toHaveCount(2);
});

it('can update a theme', function () {
    MockClient::global([
        UpdateThemeRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'update-theme-123',
        ]),
    ]);

    $data = new ThemeUpdateData(slug: 'twentytwentyfour', version: '1.1');

    $response = $this->kinsta->updateTheme('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('update-theme-123');
});

it('can bulk update themes', function () {
    MockClient::global([
        BulkUpdateThemesRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'bulk-update-themes-123',
        ]),
    ]);

    $data = new BulkThemeUpdateData(themeSlugs: ['twentytwentyfour', 'twentytwentythree']);

    $response = $this->kinsta->bulkUpdateThemes('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('bulk-update-themes-123');
});
