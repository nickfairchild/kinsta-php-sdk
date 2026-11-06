<?php

use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\AddSiteDomainData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\AddSiteDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\ChangePrimaryDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\DeleteSiteDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\GetSiteDomainsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\GetVerificationRecordsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get site domains', function () {
    MockClient::global([
        GetSiteDomainsRequest::class => MockResponse::make([
            'environment' => [
                'site_domains' => [
                    ['id' => 'domain-123', 'name' => 'example.com', 'uses_cloudflare_dns' => false],
                    ['id' => 'domain-456', 'name' => 'www.example.com', 'uses_cloudflare_dns' => false],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->getSiteDomains('env-123');

    expect($response->domains)->toBeArray()->toHaveCount(2)
        ->and($response->domains[0]['name'])->toBe('example.com');
});

it('can add a site domain', function () {
    $mockClient = MockClient::global([
        AddSiteDomainRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'add-domain-123',
        ]),
    ]);

    $data = new AddSiteDomainData(
        domain: 'newexample.com',
        addWithWwwSubdomain: true,
        setupType: 'avoid_downtime',
    );

    $response = $this->kinsta->addSiteDomain('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('add-domain-123');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'POST'
        && $request->resolveEndpoint() === '/sites/environments/env-123/domains'
        && $request->body()->all() === [
            'domain_name' => 'newexample.com',
            'add_with_www_subdomain' => true,
            'setup_type' => 'avoid_downtime',
        ]);
});

it('can delete a site domain', function () {
    $mockClient = MockClient::global([
        DeleteSiteDomainRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'delete-domain-123',
        ]),
    ]);

    $response = $this->kinsta->deleteSiteDomain('env-123', 'domain-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('delete-domain-123');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'DELETE'
        && $request->resolveEndpoint() === '/sites/environments/env-123/domains'
        && $request->body()->all() === ['domain_ids' => ['domain-123']]);
});

it('can delete multiple site domains', function () {
    $mockClient = MockClient::global([
        DeleteSiteDomainRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
        ]),
    ]);

    $response = $this->kinsta->deleteSiteDomain('env-123', ['domain-123', 'domain-456']);

    expect($response->operationId)->toBeNull();

    $mockClient->assertSent(fn (Request $request) => $request->body()->all() === ['domain_ids' => ['domain-123', 'domain-456']]);
});

it('can get verification records', function () {
    MockClient::global([
        GetVerificationRecordsRequest::class => MockResponse::make([
            'site_domain' => [
                'verification_records' => [
                    ['type' => 'TXT', 'name' => '_verification', 'value' => 'verify123'],
                ],
                'pointing_records' => [
                    ['type' => 'A', 'name' => 'example.com', 'value' => '1.2.3.4'],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->getVerificationRecords('domain-123');

    expect($response->records)->toBeArray()->toHaveCount(1)
        ->and($response->pointingRecords)->toBeArray()->toHaveCount(1);
});

it('can change primary domain', function () {
    $mockClient = MockClient::global([
        ChangePrimaryDomainRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'change-primary-123',
        ]),
    ]);

    $response = $this->kinsta->changePrimaryDomain('env-123', 'domain-456');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('change-primary-123');

    $mockClient->assertSent(fn (Request $request) => $request->getMethod()->value === 'PUT'
        && $request->resolveEndpoint() === '/sites/environments/env-123/change-primary-domain'
        && $request->body()->all() === ['domain_id' => 'domain-456', 'run_search_and_replace' => true]);
});
