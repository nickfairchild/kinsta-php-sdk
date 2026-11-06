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
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ToggleAdditionalSftpAccountsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ToggleSftpAccessRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ToggleSftpPasswordAccessRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

function assertSftpRequestSent(MockClient $mockClient, string $requestClass, Method $method, string $url, ?array $body = null): void
{
    $mockClient->assertSent(function (Request $request, Response $response) use ($requestClass, $method, $url, $body) {
        $pendingRequest = $response->getPendingRequest();

        return $request instanceof $requestClass
            && $pendingRequest->getMethod() === $method
            && $pendingRequest->getUrl() === 'https://api.kinsta.com/v2'.$url
            && ($body === null || $pendingRequest->body()?->all() === $body);
    });
}

it('can toggle SFTP access', function () {
    $mockClient = MockClient::global([
        ToggleSftpAccessRequest::class => MockResponse::make(['result' => null]),
    ]);

    $result = $this->kinsta->toggleSftpAccess('env-123', true);

    expect($result)->toBeTrue();
    assertSftpRequestSent($mockClient, ToggleSftpAccessRequest::class, Method::POST, '/sites/environments/env-123/ssh/set-status', ['is_enabled' => true]);
});

it('can toggle SFTP password access', function () {
    $mockClient = MockClient::global([
        ToggleSftpPasswordAccessRequest::class => MockResponse::make(['result' => null]),
    ]);

    $result = $this->kinsta->toggleSftpPasswordAccess('env-123', false);

    expect($result)->toBeTrue();
    assertSftpRequestSent($mockClient, ToggleSftpPasswordAccessRequest::class, Method::POST, '/sites/environments/env-123/ssh/set-password-status', ['is_enabled' => false]);
});

it('can get SSH status', function () {
    $mockClient = MockClient::global([
        GetSshStatusRequest::class => MockResponse::make([
            'environment' => [
                'active_container' => [
                    'is_ssh_enabled' => true,
                ],
            ],
        ]),
    ]);

    $status = $this->kinsta->getSshStatus('env-123');

    expect($status->isSshEnabled)->toBeTrue();
    assertSftpRequestSent($mockClient, GetSshStatusRequest::class, Method::GET, '/sites/environments/env-123/ssh/get-status');
});

it('can generate SFTP password', function () {
    $mockClient = MockClient::global([
        GenerateSftpPasswordRequest::class => MockResponse::make(['result' => null]),
    ]);

    $result = $this->kinsta->generateSftpPassword('env-123');

    expect($result)->toBeTrue();
    assertSftpRequestSent($mockClient, GenerateSftpPasswordRequest::class, Method::POST, '/sites/environments/env-123/ssh/generate-password');
});

it('can get SFTP IP allowlist', function () {
    $mockClient = MockClient::global([
        GetSftpIpAllowlistRequest::class => MockResponse::make([
            'environment' => [
                'active_container' => [
                    'sftp_ip_allowlist' => ['192.168.1.1', '10.0.0.1'],
                ],
            ],
        ]),
    ]);

    $ips = $this->kinsta->getSftpIpAllowlist('env-123');

    expect($ips)->toBe(['192.168.1.1', '10.0.0.1']);
    assertSftpRequestSent($mockClient, GetSftpIpAllowlistRequest::class, Method::GET, '/sites/environments/env-123/ssh/get-allowed-ips');
});

it('can set SFTP IP allowlist', function () {
    $mockClient = MockClient::global([
        SetSftpIpAllowlistRequest::class => MockResponse::make(['result' => null]),
    ]);

    $result = $this->kinsta->setSftpIpAllowlist('env-123', ['192.168.1.1']);

    expect($result)->toBeTrue();
    assertSftpRequestSent($mockClient, SetSftpIpAllowlistRequest::class, Method::POST, '/sites/environments/env-123/ssh/set-allowed-ips', ['ip_allowlist' => ['192.168.1.1']]);
});

it('can get SFTP config', function () {
    $mockClient = MockClient::global([
        GetSftpConfigRequest::class => MockResponse::make([
            'site' => ['id' => 'site-123'],
            'port' => '12345',
            'name' => 'mysite',
            'host' => '35.1.2.3',
            'user' => 'mysite',
            'ssh_command' => 'ssh mysite@35.1.2.3 -p 12345',
        ]),
    ]);

    $config = $this->kinsta->getSftpConfig('site-123', 'env-123');

    expect($config->host)->toBe('35.1.2.3')
        ->and($config->port)->toBe('12345')
        ->and($config->username)->toBe('mysite')
        ->and($config->sshCommand)->toBe('ssh mysite@35.1.2.3 -p 12345');
    assertSftpRequestSent($mockClient, GetSftpConfigRequest::class, Method::GET, '/sites/site-123/environments/env-123/ssh/config');
});

it('can change SFTP password expiration', function () {
    $mockClient = MockClient::global([
        ChangeSftpPasswordExpirationRequest::class => MockResponse::make(['result' => null]),
    ]);

    $result = $this->kinsta->changeSftpPasswordExpiration('env-123', 'days_30');

    expect($result)->toBeTrue();
    assertSftpRequestSent($mockClient, ChangeSftpPasswordExpirationRequest::class, Method::POST, '/sites/environments/env-123/ssh/change-expiration-interval', ['exp_interval' => 'days_30']);
});

it('can get SFTP password', function () {
    $mockClient = MockClient::global([
        GetSftpPasswordRequest::class => MockResponse::make([
            'environment' => [
                'id' => 'env-123',
                'sftp_password' => 'current-password-123',
            ],
        ]),
    ]);

    $password = $this->kinsta->getSftpPassword('env-123');

    expect($password->password)->toBe('current-password-123');
    assertSftpRequestSent($mockClient, GetSftpPasswordRequest::class, Method::GET, '/sites/environments/env-123/ssh/password');
});

it('can add additional SFTP account', function () {
    $mockClient = MockClient::global([
        AddAdditionalSftpAccountRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'add-sftp-123',
        ]),
    ]);

    $data = new SftpAccountData(username: 'newuser', password: 'password123', rootDirectory: '/dir_1', permission: 'write');

    $response = $this->kinsta->addAdditionalSftpAccount('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('add-sftp-123');
    assertSftpRequestSent($mockClient, AddAdditionalSftpAccountRequest::class, Method::POST, '/sites/environments/env-123/additional-sftp-accounts', [
        'username' => 'newuser',
        'password' => 'password123',
        'root_directory' => '/dir_1',
        'permission' => 'write',
    ]);
});

it('omits optional fields when adding additional SFTP account', function () {
    $mockClient = MockClient::global([
        AddAdditionalSftpAccountRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'add-sftp-123',
        ]),
    ]);

    $this->kinsta->addAdditionalSftpAccount('env-123', new SftpAccountData(username: 'newuser', password: 'password123'));

    assertSftpRequestSent($mockClient, AddAdditionalSftpAccountRequest::class, Method::POST, '/sites/environments/env-123/additional-sftp-accounts', [
        'username' => 'newuser',
        'password' => 'password123',
    ]);
});

it('can get additional SFTP accounts', function () {
    $mockClient = MockClient::global([
        GetAdditionalSftpAccountsRequest::class => MockResponse::make([
            'environment' => [
                'additional_sftp_accounts' => [
                    [
                        'id' => 'acc-1',
                        'username' => 'user1',
                        'root_directory' => '/dir_1',
                        'is_active' => true,
                        'permission' => 'read',
                    ],
                ],
                'container_info' => [
                    'additional_sftp_accounts_port' => '2222',
                ],
            ],
        ]),
    ]);

    $accounts = $this->kinsta->getAdditionalSftpAccounts('env-123');

    expect($accounts->accounts)->toHaveCount(1)
        ->and($accounts->accounts[0]->id)->toBe('acc-1')
        ->and($accounts->accounts[0]->rootDirectory)->toBe('/dir_1')
        ->and($accounts->accounts[0]->permission)->toBe('read')
        ->and($accounts->port)->toBe('2222');
    assertSftpRequestSent($mockClient, GetAdditionalSftpAccountsRequest::class, Method::GET, '/sites/environments/env-123/additional-sftp-accounts');
});

it('can toggle additional SFTP accounts', function () {
    $mockClient = MockClient::global([
        ToggleAdditionalSftpAccountsRequest::class => MockResponse::make([
            'message' => 'Toggling additional SFTP accounts in progress',
            'status' => 202,
            'operation_id' => 'toggle-sftp-accounts-123',
        ], 202),
    ]);

    $response = $this->kinsta->toggleAdditionalSftpAccounts('env-123', true);

    expect($response->operationId)->toBe('toggle-sftp-accounts-123')
        ->and($response->status)->toBe(202);
    assertSftpRequestSent($mockClient, ToggleAdditionalSftpAccountsRequest::class, Method::PUT, '/sites/environments/env-123/additional-sftp-accounts/toggle-status', ['enabled' => true]);
});

it('can remove additional SFTP account', function () {
    $mockClient = MockClient::global([
        RemoveAdditionalSftpAccountRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'remove-sftp-123',
        ], 202),
    ]);

    $response = $this->kinsta->removeAdditionalSftpAccount('account-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('remove-sftp-123');
    assertSftpRequestSent($mockClient, RemoveAdditionalSftpAccountRequest::class, Method::DELETE, '/sites/environments/additional-sftp-accounts/account-123');
});
