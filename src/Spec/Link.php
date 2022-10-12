<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Link implements SpecObject
{

	public ?string $operationRef;

	public ?string $operationId;

	/** @var array<string, mixed> */
	public array $parameters;

	/** @var mixed */
	public $requestBody;

	public ?string $description;

	public ?Server $server;

	public function toArray(): array
	{
		return [
			'operationRef' => $this->operationRef,
			'operationId' => $this->operationId,
			'parameters' => $this->parameters,
			'requestBody' => $this->requestBody,
			'description' => $this->description,
			'server' => $this->server !== null ? $this->server->toArray() : null,
		];
	}

}
