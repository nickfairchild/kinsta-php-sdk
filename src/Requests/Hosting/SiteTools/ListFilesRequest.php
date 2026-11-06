<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\FileListResult;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListFilesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $envId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/environments/'.$this->envId.'/file-list';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return FileListResult::fromResponse($response->json());
    }
}
