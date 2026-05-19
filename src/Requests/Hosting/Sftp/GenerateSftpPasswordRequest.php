<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp;

use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpPassword;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GenerateSftpPasswordRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected string $envId) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/ssh/generate-password";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return SftpPassword::fromResponse($response->json());
    }
}
