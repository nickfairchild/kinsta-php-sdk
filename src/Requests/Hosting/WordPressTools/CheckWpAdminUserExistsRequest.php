<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools;

use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserResult;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CheckWpAdminUserExistsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected string $email,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/wpa-user-exists";
    }

    protected function defaultBody(): array
    {
        return [
            'email' => $this->email,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return WpAdminUserResult::fromResponse($response->json());
    }
}
