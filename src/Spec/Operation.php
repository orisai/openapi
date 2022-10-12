<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Operation implements SpecObject
{

	/** @var list<string> */
	public array $tags;

	public ?string $summary;

	public ?string $description;

	public ?ExternalDocumentation $externalDocs;

	public ?string $operationId;

	/** @var list<Parameter|Reference> */
	public array $parameters;

	/** @var RequestBody|Reference|null */
	public $requestBody;

	public ?Responses $responses;

	/** @var array<string, Callback|Reference> */
	public array $callbacks;

	public bool $deprecated;

	/** @var list<SecurityRequirement> */
	public array $security;

	/** @var list<Server> */
	public array $servers;

	public function toArray(): array
	{
		return [
			'tags' => $this->tags,
			'summary' => $this->summary,
			'description' => $this->description,
			'externalDocs' => $this->externalDocs !== null ? $this->externalDocs->toArray() : null,
			'operationId' => $this->operationId,
			'parameters' => SpecUtils::specsToArray($this->parameters),
			'requestBody' => $this->requestBody !== null ? $this->requestBody->toArray() : null,
			'responses' => $this->responses !== null ? $this->responses->toArray() : null,
			'callbacks' => SpecUtils::specsToArray($this->callbacks),
			'deprecated' => $this->deprecated,
			'security' => SpecUtils::specsToArray($this->security),
			'servers' => SpecUtils::specsToArray($this->servers),
		];
	}

}
