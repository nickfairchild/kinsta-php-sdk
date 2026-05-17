<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Domains;

use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecord;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDNSRecordsForDomain extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $domainId) {}

    public function resolveEndpoint(): string
    {
        return '/domains/'.$this->domainId.'/dns-records';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return DNSRecord::collect($response->json('domain.dns_records'));
    }
}
