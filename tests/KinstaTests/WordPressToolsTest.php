<?php

use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\CheckWpAdminUserExistsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\CreateWpAdminUserRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GeneratePhpMyAdminLinkRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWordPressPluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWordPressThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWpAdminLoginLinkRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\RunWpCliCommandRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can run WP-CLI command', function () {
    MockClient::global([
        RunWpCliCommandRequest::class => MockResponse::make([
            'output' => 'Success: Command executed',
        ]),
    ]);

    $result = $this->kinsta->runWpCliCommand('env-123', 'plugin list --allow-root');

    expect($result->output)->toBe('Success: Command executed');
});

it('can generate phpMyAdmin link', function () {
    MockClient::global([
        GeneratePhpMyAdminLinkRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'pma-link-123',
        ]),
    ]);

    $response = $this->kinsta->generatePhpMyAdminLink('env-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('pma-link-123');
});

it('can get WordPress plugins', function () {
    MockClient::global([
        GetWordPressPluginsRequest::class => MockResponse::make([
            'wp_plugins' => [
                ['name' => 'Akismet', 'version' => '5.3'],
                ['name' => 'Hello Dolly', 'version' => '1.7.2'],
            ],
        ]),
    ]);

    $plugins = $this->kinsta->getWordPressPlugins('env-123');

    expect($plugins->plugins)->toBeArray()->toHaveCount(2);
});

it('can get WordPress themes', function () {
    MockClient::global([
        GetWordPressThemesRequest::class => MockResponse::make([
            'wp_themes' => [
                ['name' => 'Twenty Twenty-Four', 'version' => '1.0'],
                ['name' => 'Twenty Twenty-Three', 'version' => '1.2'],
            ],
        ]),
    ]);

    $themes = $this->kinsta->getWordPressThemes('env-123');

    expect($themes->themes)->toBeArray()->toHaveCount(2);
});

it('can get WP admin login link', function () {
    MockClient::global([
        GetWpAdminLoginLinkRequest::class => MockResponse::make([
            'login_url' => 'https://example.com/wp-admin?token=abc123',
        ]),
    ]);

    $result = $this->kinsta->getWpAdminLoginLink('env-123', 'user@example.com');

    expect($result->loginUrl)->toBe('https://example.com/wp-admin?token=abc123');
});

it('can check if WP admin user exists', function () {
    MockClient::global([
        CheckWpAdminUserExistsRequest::class => MockResponse::make([
            'exists' => true,
        ]),
    ]);

    $result = $this->kinsta->checkWpAdminUserExists('env-123', 'user@example.com');

    expect($result->exists)->toBeTrue();
});

it('can create WP admin user', function () {
    MockClient::global([
        CreateWpAdminUserRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'create-wp-user-123',
        ]),
    ]);

    $data = new WpAdminUserData(
        email: 'newuser@example.com',
        password: 'password123',
        firstName: 'John',
        lastName: 'Doe',
    );

    $response = $this->kinsta->createWpAdminUser('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('create-wp-user-123');
});
