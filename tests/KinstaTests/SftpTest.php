<?php

use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpAccountData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\AddAdditionalSftpAccountRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ChangeSftpPasswordExpirationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GenerateSftpPasswordRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetAdditionalSftpAccountsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetSftpConfigRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetSftpIpAllowlistRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetSftpPasswordRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetSshStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\RemoveAdditionalSftpAccountRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\SetSftpIpAllowlistRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ToggleSftpAccessRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can toggle SFTP access', function () {
    MockClient::global([
        ToggleSftpAccessRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'toggle-sftp-123',
        ]),
    ]);

    $response = $this->kinsta->toggleSftpAccess('env-123', true);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('toggle-sftp-123');
});

it('can get SSH status', function () {
    MockClient::global([
        GetSshStatusRequest::class => MockResponse::make([
            'ssh_enabled' => true,
            'sftp_enabled' => true,
        ]),
    ]);

    $status = $this->kinsta->getSshStatus('env-123');

    expect($status->sshEnabled)->toBeTrue()
        ->and($status->sftpEnabled)->toBeTrue();
});

it('can generate SFTP password', function () {
    MockClient::global([
        GenerateSftpPasswordRequest::class => MockResponse::make([
            'password' => 'generated-password-123',
            'expiration_time' => 3600,
        ]),
    ]);

    $password = $this->kinsta->generateSftpPassword('env-123');

    expect($password->password)->toBe('generated-password-123')
        ->and($password->expirationTime)->toBe(3600);
});

it('can get SFTP IP allowlist', function () {
    MockClient::global([
        GetSftpIpAllowlistRequest::class => MockResponse::make([
            'allowed_ips' => ['192.168.1.1', '10.0.0.1'],
        ]),
    ]);

    $ips = $this->kinsta->getSftpIpAllowlist('env-123');

    expect($ips)->toBeArray()->toHaveCount(2);
});

it('can set SFTP IP allowlist', function () {
    MockClient::global([
        SetSftpIpAllowlistRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'set-ips-123',
        ]),
    ]);

    $response = $this->kinsta->setSftpIpAllowlist('env-123', ['192.168.1.1']);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('set-ips-123');
});

it('can get SFTP config', function () {
    MockClient::global([
        GetSftpConfigRequest::class => MockResponse::make([
            'host' => 'sftp.kinsta.com',
            'port' => '22',
            'username' => 'user',
        ]),
    ]);

    $config = $this->kinsta->getSftpConfig('site-123', 'env-123');

    expect($config->host)->toBe('sftp.kinsta.com')
        ->and($config->port)->toBe('22')
        ->and($config->username)->toBe('user');
});

it('can change SFTP password expiration', function () {
    MockClient::global([
        ChangeSftpPasswordExpirationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'change-exp-123',
        ]),
    ]);

    $response = $this->kinsta->changeSftpPasswordExpiration('env-123', 7200);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('change-exp-123');
});

it('can get SFTP password', function () {
    MockClient::global([
        GetSftpPasswordRequest::class => MockResponse::make([
            'password' => 'current-password-123',
        ]),
    ]);

    $password = $this->kinsta->getSftpPassword('env-123');

    expect($password->password)->toBe('current-password-123');
});

it('can add additional SFTP account', function () {
    MockClient::global([
        AddAdditionalSftpAccountRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'add-sftp-123',
        ]),
    ]);

    $data = new SftpAccountData(username: 'newuser', password: 'password123');

    $response = $this->kinsta->addAdditionalSftpAccount('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('add-sftp-123');
});

it('can get additional SFTP accounts', function () {
    MockClient::global([
        GetAdditionalSftpAccountsRequest::class => MockResponse::make([
            'additional_sftp_accounts' => [
                ['username' => 'user1', 'home_directory' => '/home/user1', 'is_active' => true],
            ],
        ]),
    ]);

    $accounts = $this->kinsta->getAdditionalSftpAccounts('env-123');

    expect($accounts->accounts)->toBeArray()->toHaveCount(1);
});

it('can remove additional SFTP account', function () {
    MockClient::global([
        RemoveAdditionalSftpAccountRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'remove-sftp-123',
        ]),
    ]);

    $response = $this->kinsta->removeAdditionalSftpAccount('account-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('remove-sftp-123');
});
