<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\Company\CompanyUser;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetCompanyUsersRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $companyId
    ) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/users';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return CompanyUser::collect($response->json('company.users'));
    }
}
