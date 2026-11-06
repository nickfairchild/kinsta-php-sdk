<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools;

use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\PhpMyAdminLink;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GeneratePhpMyAdminLinkRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/pma-login-token";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return PhpMyAdminLink::fromResponse($response->json());
    }
}
