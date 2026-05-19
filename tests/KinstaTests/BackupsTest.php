<?php

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\AddManualBackupRequestData;
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\RestoreBackupRequestData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\AddManualBackupRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\GetBackupsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\GetDownloadableBackupsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\RemoveBackupRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\RestoreBackupRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('lists all backups for an environment', function () {
    MockClient::global([
        GetBackupsRequest::class => MockResponse::make([
            'environment' => [
                'display_name' => 'Production',
                'backups' => [
                    [
                        'id' => 1,
                        'name' => 'daily-backup',
                        'note' => 'Automatic daily backup',
                        'type' => 'automatic',
                        'created_at' => 1234567890,
                    ],
                    [
                        'id' => 2,
                        'name' => 'manual-backup',
                        'note' => null,
                        'type' => 'manual',
                        'created_at' => 1234567900,
                    ],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->backups('env-123');

    expect($response->displayName)->toBe('Production')
        ->and($response->backups)->toBeArray()->toHaveCount(2)
        ->and($response->backups[0]->id)->toBe(1)
        ->and($response->backups[0]->name)->toBe('daily-backup')
        ->and($response->backups[0]->note)->toBe('Automatic daily backup')
        ->and($response->backups[0]->type)->toBe('automatic')
        ->and($response->backups[1]->note)->toBeNull();
});

it('gets downloadable backups for an environment', function () {
    MockClient::global([
        GetDownloadableBackupsRequest::class => MockResponse::make([
            'environment' => [
                'display_name' => 'Production',
                'downloadable_backups' => [
                    [
                        'id' => 'abc-123-def',
                        'created_at' => 1234567890,
                        'download_link' => 'https://kinsta.com/backups/abc-123-def.zip',
                        'expires_at' => 1234599999,
                        'is_generation_in_progress' => false,
                    ],
                    [
                        'id' => 'xyz-789-uvw',
                        'created_at' => 1234568000,
                        'download_link' => null,
                        'expires_at' => 1234600000,
                        'is_generation_in_progress' => true,
                    ],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->downloadableBackups('env-123');

    expect($response->displayName)->toBe('Production')
        ->and($response->downloadableBackups)->toBeArray()->toHaveCount(2)
        ->and($response->downloadableBackups[0]->id)->toBe('abc-123-def')
        ->and($response->downloadableBackups[0]->downloadLink)->toBe('https://kinsta.com/backups/abc-123-def.zip')
        ->and($response->downloadableBackups[0]->isGenerationInProgress)->toBeFalse()
        ->and($response->downloadableBackups[1]->downloadLink)->toBeNull()
        ->and($response->downloadableBackups[1]->isGenerationInProgress)->toBeTrue();
});

it('can restore a backup', function () {
    MockClient::global([
        RestoreBackupRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'restore-op-123',
        ]),
    ]);

    $payload = new RestoreBackupRequestData(
        backupId: 42,
        notifiedUserId: 'user-123',
    );

    $response = $this->kinsta->restoreBackup('env-target-456', $payload);

    expect($response->message)->toBeString()->toBe('Successfully finished request')
        ->and($response->status)->toBeInt()->toBe(200)
        ->and($response->operationId)->toBeString()->toBe('restore-op-123');
});

it('can add a manual backup', function () {
    MockClient::global([
        AddManualBackupRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'backup-op-789',
        ]),
    ]);

    $payload = new AddManualBackupRequestData(
        tag: 'pre-deployment-backup'
    );

    $response = $this->kinsta->addManualBackup('env-123', $payload);

    expect($response->message)->toBeString()->toBe('Successfully finished request')
        ->and($response->status)->toBeInt()->toBe(200)
        ->and($response->operationId)->toBeString()->toBe('backup-op-789');
});

it('can remove a backup', function () {
    MockClient::global([
        RemoveBackupRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'delete-op-456',
        ]),
    ]);

    $response = $this->kinsta->removeBackup(999);

    expect($response->message)->toBeString()->toBe('Successfully finished request')
        ->and($response->status)->toBeInt()->toBe(200)
        ->and($response->operationId)->toBeString()->toBe('delete-op-456');
});
