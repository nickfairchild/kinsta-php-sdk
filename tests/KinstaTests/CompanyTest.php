<?php

use Nickfairchild\KinstaPhpSdk\Dto\Company\ActivityLogs;
use Nickfairchild\KinstaPhpSdk\Dto\Company\PluginThemePayload;
use Nickfairchild\KinstaPhpSdk\Dto\Company\SiteCapabilityData;
use Nickfairchild\KinstaPhpSdk\Enums\Category;
use Nickfairchild\KinstaPhpSdk\Enums\Language;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetActivityLogsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetAPIKeysRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyPluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyUserRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetCompanyUsersRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\GetRegionsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\SetCompanyCapabilityRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Company\SetSiteCapabilitiesRequest;
use Saloon\Enums\Method;
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
                            'type_2fa' => 'email',
                        ],
                        'capabilities' => [
                            [
                                'id' => 'cap-1',
                                'type' => 'company',
                                'role' => 'company-admin',
                                'company_id' => 'company-id',
                                'site_id' => null,
                            ],
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
            ->and($response->fullName)->toBeString()
            ->and($response->type2fa)->toBe('email')
            ->and($response->capabilities[0]->role)->toBe('company-admin')
            ->and($response->capabilities[0]->companyId)->toBe('company-id')
            ->and($response->capabilities[0]->siteId)->toBeNull();
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

    $response = $this->kinsta->activityLogs();

    expect($response)->toBeInstanceOf(ActivityLogs::class);

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
});

it('can get a list of activity logs with filters', function () {
    $mock = MockClient::global([
        GetActivityLogsRequest::class => MockResponse::make([
            'company' => [
                'activity_logs' => [
                    'items' => [],
                    'total' => 0,
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->activityLogs(
        limit: 5,
        offset: 10,
        category: Category::SITE_ACTIONS,
        siteId: 'site-1',
        language: Language::JAPANESE,
    );

    expect($response->items)->toBe([])
        ->and($response->total)->toBe(0);

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->query()->all() === [
            'limit' => 5,
            'offset' => 10,
            'category' => 'siteActions',
            'site_id' => 'site-1',
            'language' => 'ja',
        ]);
});

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

it('can get a company user by id', function () {
    MockClient::global([
        GetCompanyUserRequest::class => MockResponse::make([
            'company' => [
                'user_by_id' => [
                    'user' => [
                        'id' => 'user-1',
                        'email' => 'john@example.com',
                        'full_name' => 'John Doe',
                        'type_2fa' => null,
                    ],
                    'capabilities' => [
                        [
                            'role' => 'site-developer',
                            'id_company' => null,
                            'id_site' => 'site-1',
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->companyUser('user-1');

    MockClient::global()->assertSent(function (GetCompanyUserRequest $request) {
        return $request->getMethod() === Method::GET
            && $request->resolveEndpoint() === '/company/company-id/users/user-1';
    });

    expect($response->id)->toBe('user-1')
        ->and($response->image)->toBeNull()
        ->and($response->type2fa)->toBeNull()
        ->and($response->capabilities[0]->role)->toBe('site-developer')
        ->and($response->capabilities[0]->companyId)->toBeNull()
        ->and($response->capabilities[0]->siteId)->toBe('site-1');
});

it('can set a company capability for a user', function () {
    MockClient::global([
        SetCompanyCapabilityRequest::class => MockResponse::make([
            'operation_id' => 'op-1',
            'message' => 'User capabilities updated',
            'status' => 200,
        ]),
    ]);

    $response = $this->kinsta->setCompanyCapability('user-1', 'company-developer');

    MockClient::global()->assertSent(function (SetCompanyCapabilityRequest $request) {
        return $request->getMethod() === Method::POST
            && $request->resolveEndpoint() === '/company/company-id/users/company-capabilities'
            && $request->body()->all() === ['user_id' => 'user-1', 'role' => 'company-developer'];
    });

    expect($response->operationId)->toBe('op-1')
        ->and($response->status)->toBe(200);
});

it('can set site capabilities for users', function () {
    MockClient::global([
        SetSiteCapabilitiesRequest::class => MockResponse::make([
            'operation_id' => 'op-2',
            'message' => 'User capabilities updated',
            'status' => 200,
        ]),
    ]);

    $response = $this->kinsta->setSiteCapabilities(
        ['user-1', 'user-2'],
        [new SiteCapabilityData(siteId: 'site-1', role: 'site-admin')],
    );

    MockClient::global()->assertSent(function (SetSiteCapabilitiesRequest $request) {
        return $request->getMethod() === Method::POST
            && $request->resolveEndpoint() === '/company/company-id/users/site-capabilities'
            && $request->body()->all() === [
                'user_ids' => ['user-1', 'user-2'],
                'site_capabilities' => [['site_id' => 'site-1', 'role' => 'site-admin']],
            ];
    });

    expect($response->operationId)->toBe('op-2');
});

it('supports every documented activity log language', function () {
    expect(array_map(fn (Language $language) => $language->value, Language::cases()))
        ->toBe(['da', 'de', 'en', 'es', 'fr', 'it', 'ja', 'nl', 'pt', 'sv']);
});
