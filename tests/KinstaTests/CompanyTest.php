<?php

use Nickfairchild\KinstaPhpSdk\Dto\Company\PluginThemePayload;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetActivityLogsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetAPIKeysRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyPluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyUsersRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetRegionsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get the company users', function () {
    MockClient::global([
        GetCompanyUsersRequest::class => MockResponse::make([
            'company' => [
                'users' => [
                    [
                        'user' => [
                            'id' => 'user-1',
                            'email' => 'john@exapmle.com',
                            'image' => 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50',
                            'full_name' => 'John Doe',
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $responses = $this->kinsta->companyUsers();

    foreach ($responses as $response) {
        expect($response->id)->toBeString()
            ->and($response->email)->toBeString()
            ->and($response->image)->toBeString()
            ->and($response->fullName)->toBeString();
    }
});

it('can get a list of regions', function () {
    MockClient::global([
        GetRegionsRequest::class => MockResponse::make([
            'company' => [
                'available_regions' => [
                    [
                        'name' => 'us-east-1',
                        'region' => 'us-east-1',
                    ],
                ],
            ],
        ]),
    ]);

    $responses = $this->kinsta->regions();

    foreach ($responses as $response) {
        expect($response->name)->toBeString()
            ->and($response->region)->toBeString();
    }
});

it('can get a list of api keys', function () {
    MockClient::global([
        GetAPIKeysRequest::class => MockResponse::make([
            'api_keys' => [
                [
                    'id' => '123',
                    'name' => 'Public API Key',
                    'status' => 'active',
                    'expires_at' => '2027-01-01T00:00:00Z',
                ],
            ],
        ]),
    ]);

    $responses = $this->kinsta->apiKeys();

    foreach ($responses as $response) {
        expect($response->id)->toBeString()
            ->and($response->name)->toBeString()
            ->and($response->status)->toBeString()->toBe('active')
            ->and($response->expiresAt)->toBeString();
    }
});

it('can get a list of activity logs', function () {
    MockClient::global([
        GetActivityLogsRequest::class => MockResponse::make([
            'company' => [
                'activity_logs' => [
                    'items' => [
                        [
                            'id' => 123,
                            'site_id' => '456',
                            'created_at' => 1665382600770,
                            'is_done' => true,
                            'has_failed' => false,
                            'has_warning' => false,
                            'type' => 'site_created',
                            'public_error' => 'Site creation failed',
                            'ip_address' => '127.0.0.1',
                            'descriptions' => ['Site creation failed'],
                            'initiated_by' => [],
                            'user_agent_info' => [],
                            'api_key_info' => [],
                        ],
                    ],
                    'total' => 1,
                ],
            ],
        ]),
    ]);

    $responses = $this->kinsta->activityLogs();

    foreach ($responses as $response) {
        expect($response->items)->toBeArray()
            ->and($response->total)->toBeInt()->toBe(1);

        foreach ($response->items as $item) {
            expect($item->id)->toBeInt()
                ->and($item->siteId)->toBeString()
                ->and($item->createdAt)->toBeInt()
                ->and($item->isDone)->toBeBool()->toBeTrue()
                ->and($item->hasFailed)->toBeBool()->toBeFalse()
                ->and($item->hasWarning)->toBeBool()->toBeFalse()
                ->and($item->type)->toBeString()
                ->and($item->publicError)->toBeString()
                ->and($item->ipAddress)->toBeString()
                ->and($item->descriptions)->toBeArray()
                ->and($item->initiatedBy)->toBeArray()
                ->and($item->userAgentInfo)->toBeArray()
                ->and($item->apiKeyInfo)->toBeArray();
        }
    }
});

// it('can get a list of activity logs with filters', function () {});

it('can get a list of plugins', function () {
    MockClient::global([
        GetCompanyPluginsRequest::class => MockResponse::make([
            'company' => [
                'plugins' => [
                    'total' => 1,
                    'last_updated_at' => '2023-01-01T00:00:00Z',
                    'items' => [
                        [
                            'name' => 'elementor',
                            'title' => 'Elementor',
                            'description' => 'Popular page builder.',
                            'latest_version' => '3.22.1',
                            'is_latest_version_vulnerable' => false,
                            'environment_count' => 5,
                            'update_count' => 2,
                            'environments' => [
                                [
                                    'id' => 'env-1',
                                    'site_display_name' => 'My Awesome Site',
                                    'display_name' => 'Live',
                                    'plugin_status' => 'active',
                                    'plugin_update' => 'available',
                                    'plugin_version' => '1.0.0',
                                    'is_plugin_version_vulnerable' => false,
                                    'plugin_update_version' => '1.0.1',
                                    'is_plugin_update_version_vulnerable' => false,
                                    'plugin_update_status' => 'updated',
                                    'auto_update_type' => 'Kinsta Automatic Update',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $payload = new PluginThemePayload(
        offset: 0,
        limit: 10,
    );
    $response = $this->kinsta->companyPlugins($payload);

    expect($response['total'])->toBeInt()->toBe(1)
        ->and($response['last_updated_at'])->toBeString()
        ->and($response['items'])->toBeArray();

    foreach ($response['items'] as $item) {
        expect($item->name)->toBeString()
            ->and($item->title)->toBeString();
    }
});

it('can get a list of themes', function () {
    MockClient::global([
        GetCompanyThemesRequest::class => MockResponse::make([
            'company' => [
                'themes' => [
                    'total' => 1,
                    'last_updated_at' => '2023-01-01T00:00:00Z',
                    'items' => [
                        [
                            'name' => 'twentytwentyone',
                            'title' => 'Twenty Twenty-One',
                            'description' => 'Default WordPress theme for 2021.',
                            'latest_version' => '1.0',
                            'is_latest_version_vulnerable' => false,
                            'environment_count' => 5,
                            'update_count' => 2,
                            'environments' => [
                                [
                                    'id' => 'env-1',
                                    'site_display_name' => 'My Awesome Site',
                                    'display_name' => 'Live',
                                    'theme_status' => 'active',
                                    'theme_update' => 'available',
                                    'theme_version' => '1.0.0',
                                    'is_theme_version_vulnerable' => false,
                                    'theme_update_version' => '1.0.1',
                                    'is_theme_update_version_vulnerable' => false,
                                    'theme_update_status' => 'updated',
                                    'auto_update_type' => 'Kinsta Automatic Update',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $payload = new PluginThemePayload(
        offset: 0,
        limit: 10,
    );
    $response = $this->kinsta->companyThemes($payload);

    expect($response['total'])->toBeInt()->toBe(1)
        ->and($response['last_updated_at'])->toBeString()
        ->and($response['items'])->toBeArray();

    foreach ($response['items'] as $item) {
        expect($item->name)->toBeString()
            ->and($item->title)->toBeString();
    }
});
