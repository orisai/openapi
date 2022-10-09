<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Link
{

	public ?string $operationRef;

	public ?string $operationId;

	/** @var array<string, mixed> */
	public array $parameters;

	/** @var mixed */
	public $requestBody;

	public ?string $description;

	public ?Server $server;

}
