<?php

use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\FileListData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\PhpAllocationData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeEnvironmentPhpAllocationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeEnvironmentWebrootRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeSitePhpAllocationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ClearSiteCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ListFilesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ModifyPhpVersionRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\RestartPhpEngineRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can clear site cache', function () {
    MockClient::global([
        ClearSiteCacheRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clear-cache-123',
        ]),
    ]);

    $response = $this->kinsta->clearSiteCache('env-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('clear-cache-123');
});

it('can restart PHP engine', function () {
    MockClient::global([
        RestartPhpEngineRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'restart-php-123',
        ]),
    ]);

    $response = $this->kinsta->restartPhpEngine('env-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('restart-php-123');
});

it('can modify PHP version', function () {
    MockClient::global([
        ModifyPhpVersionRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'modify-php-123',
        ]),
    ]);

    $response = $this->kinsta->modifyPhpVersion('env-123', '8.2');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('modify-php-123');
});

it('can change site PHP allocation', function () {
    MockClient::global([
        ChangeSitePhpAllocationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'change-php-alloc-123',
        ]),
    ]);

    $data = new PhpAllocationData(
        memory: '512M',
        maxExecutionTime: '300',
        httpMaxConnections: '50',
    );

    $response = $this->kinsta->changeSitePhpAllocation('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('change-php-alloc-123');
});

it('can change environment PHP allocation', function () {
    MockClient::global([
        ChangeEnvironmentPhpAllocationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'change-env-php-123',
        ]),
    ]);

    $data = new PhpAllocationData(
        memory: '512M',
        maxExecutionTime: '300',
        httpMaxConnections: '50',
    );

    $response = $this->kinsta->changeEnvironmentPhpAllocation('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('change-env-php-123');
});

it('can change environment webroot', function () {
    MockClient::global([
        ChangeEnvironmentWebrootRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'change-webroot-123',
        ]),
    ]);

    $response = $this->kinsta->changeEnvironmentWebroot('env-123', '/wp');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('change-webroot-123');
});

it('can list files', function () {
    MockClient::global([
        ListFilesRequest::class => MockResponse::make([
            'environment_id' => 'env-123',
            'files' => [
                'index.php',
                'wp-config.php',
                'wp-content',
            ],
        ]),
    ]);

    $data = new FileListData(path: '/public');

    $response = $this->kinsta->listFiles('env-123', $data);

    expect($response->environmentId)->toBe('env-123')
        ->and($response->files)->toBeArray()->toHaveCount(3);
});
