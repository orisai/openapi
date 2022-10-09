<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Operation
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

}
