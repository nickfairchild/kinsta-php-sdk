<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools;

use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserResult;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class CheckWpAdminUserExistsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $envId,
        protected string $email,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/wpa-user-exists";
    }

    protected function defaultQuery(): array
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
