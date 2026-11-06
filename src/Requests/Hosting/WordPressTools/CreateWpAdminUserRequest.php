<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools;

use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserData;
use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserResult;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateWpAdminUserRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected WpAdminUserData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/wpa-create-user";
    }

    protected function defaultBody(): array
    {
        return [
            'email' => $this->data->email,
            'first_name' => $this->data->firstName,
            'last_name' => $this->data->lastName,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return WpAdminUserResult::fromResponse($response->json());
    }
}
