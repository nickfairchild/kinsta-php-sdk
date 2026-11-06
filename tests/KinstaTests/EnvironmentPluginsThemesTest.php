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
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

function assertPluginsThemesRequestSent(MockClient $mockClient, string $requestClass, Method $method, string $url, ?array $body = null): void
{
    $mockClient->assertSent(function (Request $request, Response $response) use ($requestClass, $method, $url, $body) {
        $pendingRequest = $response->getPendingRequest();

        return $request instanceof $requestClass
            && $pendingRequest->getMethod() === $method
            && $pendingRequest->getUrl() === 'https://api.kinsta.com/v2'.$url
            && ($body === null || $pendingRequest->body()?->all() === $body);
    });
}

function operationMockResponse(string $operationId): MockResponse
{
    return MockResponse::make([
        'operation_id' => $operationId,
        'message' => 'Updating in progress',
        'status' => 202,
    ], 202);
}

it('can get environment plugins', function () {
    $mockClient = MockClient::global([
        GetEnvironmentPluginsRequest::class => MockResponse::make([
            'environment' => [
                'id' => 'env-123',
                'container_info' => [
                    'id' => 'container-123',
                    'wp_plugins' => [
                        'data' => [
                            ['name' => 'akismet', 'title' => 'Akismet', 'status' => 'active', 'version' => '5.3', 'update' => 'available', 'update_version' => '5.4', 'update_status' => null],
                            ['name' => 'hello-dolly', 'title' => 'Hello Dolly', 'status' => 'inactive', 'version' => '1.7.2', 'update' => 'none', 'update_version' => null, 'update_status' => null],
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->getEnvironmentPlugins('env-123');

    expect($response->plugins)->toHaveCount(2)
        ->and($response->plugins[0]['name'])->toBe('akismet');
    assertPluginsThemesRequestSent($mockClient, GetEnvironmentPluginsRequest::class, Method::GET, '/sites/environments/env-123/plugins');
});

it('can update a plugin', function () {
    $mockClient = MockClient::global([
        UpdatePluginRequest::class => operationMockResponse('update-plugin-123'),
    ]);

    $data = new PluginUpdateData(name: 'akismet', updateVersion: '5.4');

    $response = $this->kinsta->updatePlugin('env-123', $data);

    expect($response->operationId)->toBe('update-plugin-123');
    assertPluginsThemesRequestSent($mockClient, UpdatePluginRequest::class, Method::PUT, '/sites/environments/env-123/plugins', [
        'name' => 'akismet',
        'update_version' => '5.4',
    ]);
});

it('can bulk update plugins', function () {
    $mockClient = MockClient::global([
        BulkUpdatePluginsRequest::class => operationMockResponse('bulk-update-plugins-123'),
    ]);

    $data = new BulkPluginUpdateData(plugins: ['akismet', 'hello-dolly']);

    $response = $this->kinsta->bulkUpdatePlugins('env-123', $data);

    expect($response->operationId)->toBe('bulk-update-plugins-123');
    assertPluginsThemesRequestSent($mockClient, BulkUpdatePluginsRequest::class, Method::PUT, '/sites/environments/env-123/plugins/bulk-update', [
        'plugins' => [['name' => 'akismet'], ['name' => 'hello-dolly']],
    ]);
});

it('can get environment themes', function () {
    $mockClient = MockClient::global([
        GetEnvironmentThemesRequest::class => MockResponse::make([
            'environment' => [
                'id' => 'env-123',
                'container_info' => [
                    'id' => 'container-123',
                    'wp_themes' => [
                        'data' => [
                            ['name' => 'twentytwentyfour', 'title' => 'Twenty Twenty-Four', 'status' => 'active', 'version' => '1.0', 'update' => 'none', 'update_version' => null, 'update_status' => null],
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->getEnvironmentThemes('env-123');

    expect($response->themes)->toHaveCount(1)
        ->and($response->themes[0]['name'])->toBe('twentytwentyfour');
    assertPluginsThemesRequestSent($mockClient, GetEnvironmentThemesRequest::class, Method::GET, '/sites/environments/env-123/themes');
});

it('can update a theme', function () {
    $mockClient = MockClient::global([
        UpdateThemeRequest::class => operationMockResponse('update-theme-123'),
    ]);

    $data = new ThemeUpdateData(name: 'twentytwentyfour', updateVersion: '1.1');

    $response = $this->kinsta->updateTheme('env-123', $data);

    expect($response->operationId)->toBe('update-theme-123');
    assertPluginsThemesRequestSent($mockClient, UpdateThemeRequest::class, Method::PUT, '/sites/environments/env-123/themes', [
        'name' => 'twentytwentyfour',
        'update_version' => '1.1',
    ]);
});

it('can bulk update themes', function () {
    $mockClient = MockClient::global([
        BulkUpdateThemesRequest::class => operationMockResponse('bulk-update-themes-123'),
    ]);

    $data = new BulkThemeUpdateData(themes: ['twentytwentyfour', 'twentytwentythree']);

    $response = $this->kinsta->bulkUpdateThemes('env-123', $data);

    expect($response->operationId)->toBe('bulk-update-themes-123');
    assertPluginsThemesRequestSent($mockClient, BulkUpdateThemesRequest::class, Method::PUT, '/sites/environments/env-123/themes/bulk-update', [
        'themes' => [['name' => 'twentytwentyfour'], ['name' => 'twentytwentythree']],
    ]);
});
