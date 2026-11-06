<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ModifyPhpVersionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected string $phpVersion,
        protected ?bool $optOutFromAutomaticPhpUpdate = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/tools/modify-php-version';
    }

    protected function defaultBody(): array
    {
        return array_filter([
            'environment_id' => $this->envId,
            'php_version' => $this->phpVersion,
            'is_opt_out_from_automatic_php_update' => $this->optOutFromAutomaticPhpUpdate,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
