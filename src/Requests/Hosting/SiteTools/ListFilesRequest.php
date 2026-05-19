<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\FileListData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\FileListResult;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ListFilesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected FileListData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/sites/environments/'.$this->envId.'/file-list';
    }

    protected function defaultBody(): array
    {
        return [
            'site_path' => $this->data->path,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return FileListResult::fromResponse($response->json());
    }
}
