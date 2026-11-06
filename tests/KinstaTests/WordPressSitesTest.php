<?php

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\SitePayload;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CloneSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CreatePlainSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CreateSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\DeleteSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSitesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\ResetSiteRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get all sites', function () {
    $mockClient = MockClient::global([
        GetSitesRequest::class => MockResponse::make([
            'company' => [
                'sites' => [
                    [
                        'id' => 'site-123',
                        'name' => 'My WordPress Site',
                        'display_name' => 'My WordPress Site',
                        'status' => 'live',
                        'environments' => [],
                    ],
                ],
            ],
        ]),
    ]);

    $sites = $this->kinsta->sites(false);

    expect($sites)->toBeArray()->toHaveCount(1);

    $mockClient->assertSent(fn (Request $request) => $request->resolveEndpoint() === '/sites'
        && $request->query()->get('company') === 'company-id'
        && $request->query()->get('include_environments') === 'false');
});

it('omits include_environments when not set', function () {
    $mockClient = MockClient::global([
        GetSitesRequest::class => MockResponse::make(['company' => ['sites' => []]]),
    ]);

    $this->kinsta->sites();

    $mockClient->assertSent(fn (Request $request) => $request->query()->get('include_environments') === null);
});

it('can get a single site', function () {
    MockClient::global([
        GetSiteRequest::class => MockResponse::make([
            'site' => [
                'id' => 'site-123',
                'name' => 'my-wp-site',
                'display_name' => 'My WordPress Site',
                'company_id' => 'company-id',
                'status' => 'live',
                'siteLabels' => [['id' => 'label-1', 'name' => 'Client']],
                'environments' => [],
            ],
        ]),
    ]);

    $site = $this->kinsta->site('site-123');

    expect($site->id)->toBe('site-123')
        ->and($site->name)->toBe('my-wp-site')
        ->and($site->displayName)->toBe('My WordPress Site')
        ->and($site->status)->toBe('live')
        ->and($site->companyId)->toBe('company-id')
        ->and($site->siteLabels)->toBe([['id' => 'label-1', 'name' => 'Client']]);
});

it('can create a WordPress site', function () {
    $mockClient = MockClient::global([
        CreateSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'create-site-123',
            'support_reference_id' => 12345,
            'warning' => 'Some plugins could not be installed.',
        ]),
    ]);

    $payload = new SitePayload(
        displayName: 'New WordPress Site',
        region: 'us-central',
        adminEmail: 'admin@example.com',
        adminPassword: 'password123',
        adminUser: 'admin',
        siteTitle: 'My Site',
        wpLanguage: 'en_US',
        company: 'company-id',
    );

    $response = $this->kinsta->createSite($payload);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(202)
        ->and($response->operationId)->toBe('create-site-123')
        ->and($response->supportReferenceId)->toBe(12345)
        ->and($response->warning)->toBe('Some plugins could not be installed.');

    $mockClient->assertSent(function (Request $request) {
        $body = $request->body()->all();

        return $request->getMethod()->value === 'POST'
            && $request->resolveEndpoint() === '/sites'
            && $body['install_mode'] === 'new'
            && $body['company'] === 'company-id'
            && $body['is_multisite'] === false
            && $body['is_subdomain_multisite'] === false
            && ! array_key_exists('woocommerce', $body)
            && ! array_key_exists('wordpressseo', $body);
    });
});

it('can create a plain site', function () {
    MockClient::global([
        CreatePlainSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'create-plain-123',
        ]),
    ]);

    $response = $this->kinsta->createPlainSite('Plain Site', 'us-central');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(200)
        ->and($response->operationId)->toBe('create-plain-123');
});

it('can clone a site', function () {
    MockClient::global([
        CloneSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'clone-site-123',
        ]),
    ]);

    $response = $this->kinsta->cloneSite('Cloned Site', 'env-source-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(200)
        ->and($response->operationId)->toBe('clone-site-123');
});

it('can delete a site', function () {
    MockClient::global([
        DeleteSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'delete-site-123',
        ]),
    ]);

    $response = $this->kinsta->deleteSite('site-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(200)
        ->and($response->operationId)->toBe('delete-site-123');
});

it('can reset a site', function () {
    $mockClient = MockClient::global([
        ResetSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'reset-site-123',
        ]),
    ]);

    $response = $this->kinsta->resetSite('site-123', 'newpassword123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(202)
        ->and($response->operationId)->toBe('reset-site-123');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'POST'
        && $request->resolveEndpoint() === '/sites/site-123/reset-site'
        && $request->body()->all() === ['admin_password' => 'newpassword123']);
});
