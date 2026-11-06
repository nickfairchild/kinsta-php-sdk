<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SetCompanyCapabilityRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $companyId,
        protected string $userId,
        protected string $role,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/users/company-capabilities';
    }

    protected function defaultBody(): array
    {
        return [
            'user_id' => $this->userId,
            'role' => $this->role,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
