<?php

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\Environment;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CloneEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CreateEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CreatePlainEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\PushEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\CloneEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\CreateEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\CreatePlainEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\DeleteEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\GetEnvironmentRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\GetSiteEnvironmentsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteEnvironments\PushEnvironmentRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get site environments', function () {
    MockClient::global([
        GetSiteEnvironmentsRequest::class => MockResponse::make([
            'site' => [
                'environments' => [
                    [
                        'id' => 'env-123',
                        'name' => 'staging',
                        'display_name' => 'Staging',
                        'is_blocked' => false,
                        'is_premium' => false,
                        'wordpress_version' => '6.4.2',
                        'domains' => [],
                        'primaryDomain' => ['id' => 'domain-1', 'name' => 'staging.example.com', 'type' => 'live', 'site_id' => 'site-123'],
                        'cdn_cache_id' => 'cdn-1',
                        'is_opt_out_from_automatic_php_update' => true,
                        'ssh_connection' => [],
                        'container_info' => [],
                    ],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->getSiteEnvironments('site-123');

    expect($response->environments)->toBeArray()->toHaveCount(1)
        ->and($response->environments[0])->toBeInstanceOf(Environment::class)
        ->and($response->environments[0]->id)->toBe('env-123')
        ->and($response->environments[0]->name)->toBe('staging')
        ->and($response->environments[0]->primaryDomain->name)->toBe('staging.example.com')
        ->and($response->environments[0]->primaryDomain->siteId)->toBe('site-123')
        ->and($response->environments[0]->cdnEdgeCache)->toBe('cdn-1')
        ->and($response->environments[0]->isOptOutFromAutomaticPHPUpdates)->toBeTrue();
});

it('can get a single environment', function () {
    MockClient::global([
        GetEnvironmentRequest::class => MockResponse::make([
            'environment' => [
                'id' => 'env-123',
                'name' => 'staging',
                'display_name' => 'Staging',
                'is_blocked' => false,
                'is_premium' => false,
                'wordpress_version' => '6.4.2',
                'domains' => [],
                'primary_domain' => [],
                'ssh_connection' => [],
                'container_info' => [],
            ],
        ]),
    ]);

    $env = $this->kinsta->getEnvironment('env-123');

    expect($env->id)->toBe('env-123')
        ->and($env->name)->toBe('staging')
        ->and($env->displayName)->toBe('Staging');
});

it('can create an environment', function () {
    $mockClient = MockClient::global([
        CreateEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'create-env-123',
            'warning' => 'Some plugins could not be installed.',
        ]),
    ]);

    $data = new CreateEnvironmentData(
        displayName: 'staging',
        siteTitle: 'My Staging Site',
        isPremium: false,
        adminEmail: 'admin@example.com',
        adminPassword: 'password123',
        adminUser: 'admin',
        wpLanguage: 'en_US',
    );

    $response = $this->kinsta->createEnvironment('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(202)
        ->and($response->operationId)->toBe('create-env-123')
        ->and($response->warning)->toBe('Some plugins could not be installed.');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'POST'
        && $request->resolveEndpoint() === '/sites/site-123/environments'
        && $request->body()->all() === [
            'display_name' => 'staging',
            'site_title' => 'My Staging Site',
            'is_premium' => false,
            'admin_email' => 'admin@example.com',
            'admin_password' => 'password123',
            'admin_user' => 'admin',
            'wp_language' => 'en_US',
        ]);
});

it('can create a plain environment', function () {
    $mockClient = MockClient::global([
        CreatePlainEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'create-plain-123',
        ]),
    ]);

    $data = new CreatePlainEnvironmentData(displayName: 'Plain Env');

    $response = $this->kinsta->createPlainEnvironment('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('create-plain-123');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'POST'
        && $request->resolveEndpoint() === '/sites/site-123/environments/plain'
        && $request->body()->all() === ['display_name' => 'Plain Env', 'is_premium' => false]);
});

it('can clone an environment', function () {
    $mockClient = MockClient::global([
        CloneEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clone-env-123',
        ]),
    ]);

    $data = new CloneEnvironmentData(
        displayName: 'Cloned Env',
        sourceEnvId: 'env-source',
        isPremium: true,
    );

    $response = $this->kinsta->cloneEnvironment('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('clone-env-123');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'POST'
        && $request->resolveEndpoint() === '/sites/site-123/environments/clone'
        && $request->body()->all() === [
            'display_name' => 'Cloned Env',
            'is_premium' => true,
            'source_env_id' => 'env-source',
        ]);
});

it('can push an environment', function () {
    $mockClient = MockClient::global([
        PushEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'push-env-123',
        ]),
    ]);

    $data = new PushEnvironmentData(
        sourceEnvId: 'env-source',
        targetEnvId: 'env-target',
        pushDb: false,
        pushFiles: true,
        pushFilesOption: 'SPECIFIC_FILES',
        fileList: ['wp-content/themes'],
    );

    $response = $this->kinsta->pushEnvironment('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('push-env-123');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'PUT'
        && $request->resolveEndpoint() === '/sites/site-123/environments'
        && $request->body()->all() === [
            'source_env_id' => 'env-source',
            'target_env_id' => 'env-target',
            'push_db' => false,
            'push_files' => true,
            'push_files_option' => 'SPECIFIC_FILES',
            'file_list' => ['wp-content/themes'],
        ]);
});

it('can delete an environment', function () {
    $mockClient = MockClient::global([
        DeleteEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'delete-env-123',
        ]),
    ]);

    $response = $this->kinsta->deleteEnvironment('env-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('delete-env-123');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'DELETE'
        && $request->resolveEndpoint() === '/sites/environments/env-123');
});
