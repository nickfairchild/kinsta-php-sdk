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

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get site environments', function () {
    MockClient::global([
        GetSiteEnvironmentsRequest::class => MockResponse::make([
            'environments' => [
                [
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
            ],
        ]),
    ]);

    $response = $this->kinsta->getSiteEnvironments('site-123');

    expect($response->environments)->toBeArray()->toHaveCount(1)
        ->and($response->environments[0])->toBeInstanceOf(Environment::class)
        ->and($response->environments[0]->id)->toBe('env-123')
        ->and($response->environments[0]->name)->toBe('staging');
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
    MockClient::global([
        CreateEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'create-env-123',
        ]),
    ]);

    $data = new CreateEnvironmentData(
        displayName: 'New Staging',
        envType: 'staging',
    );

    $response = $this->kinsta->createEnvironment('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(202)
        ->and($response->operationId)->toBe('create-env-123');
});

it('can create a plain environment', function () {
    MockClient::global([
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
});

it('can clone an environment', function () {
    MockClient::global([
        CloneEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clone-env-123',
        ]),
    ]);

    $data = new CloneEnvironmentData(
        displayName: 'Cloned Env',
        sourceEnvId: 'env-source',
    );

    $response = $this->kinsta->cloneEnvironment('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('clone-env-123');
});

it('can push an environment', function () {
    MockClient::global([
        PushEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'push-env-123',
        ]),
    ]);

    $data = new PushEnvironmentData(
        sourceEnvId: 'env-source',
        targetEnvId: 'env-target',
    );

    $response = $this->kinsta->pushEnvironment('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('push-env-123');
});

it('can delete an environment', function () {
    MockClient::global([
        DeleteEnvironmentRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'delete-env-123',
        ]),
    ]);

    $response = $this->kinsta->deleteEnvironment('env-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('delete-env-123');
});
