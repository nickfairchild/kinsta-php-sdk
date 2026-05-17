<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Domains;

use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecord;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateDNSRecord extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $domainId,
        protected DNSRecord $dnsRecord
    ) {}

    public function resolveEndpoint(): string
    {
        return '/domains/'.$this->domainId.'/dns-records';
    }

    protected function defaultBody(): array
    {
        return [
            'type' => $this->dnsRecord->type,
            'name' => $this->dnsRecord->name,
            'ttl' => $this->dnsRecord->ttl,
            'resource_records' => $this->dnsRecord->resourceRecords,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
