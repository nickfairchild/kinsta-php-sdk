<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains;

use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\VerificationRecords;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetVerificationRecordsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $siteDomainId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/domains/{$this->siteDomainId}/verification-records";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return VerificationRecords::fromResponse($response->json());
    }
}
