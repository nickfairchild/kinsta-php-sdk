<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\Company\CompanyUser;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetCompanyUserRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $companyId,
        protected string $userId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/users/'.$this->userId;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return CompanyUser::fromResponse($response->json('company.user_by_id'));
    }
}
