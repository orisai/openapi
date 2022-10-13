<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use ReflectionProperty;

final class Link implements SpecObject
{

	public ?string $operationRef = null;

	public ?string $operationId = null;

	/** @var array<string, mixed> */
	public array $parameters = [];

	/** @var mixed */
	public $requestBody;

	public ?string $description = null;

	public ?Server $server = null;

	public function __construct()
	{
		unset($this->requestBody);
	}

	public function toArray(): array
	{
		$data = [];

		if ($this->operationRef !== null) {
			$data['operationRef'] = $this->operationRef;
		}

		if ($this->operationId !== null) {
			$data['operationId'] = $this->operationId;
		}

		if ($this->parameters !== []) {
			$data['parameters'] = $this->parameters;
		}

		$valueRef = new ReflectionProperty($this, 'requestBody');
		if ($valueRef->isInitialized($this)) {
			$data['requestBody'] = $this->requestBody;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->server !== null) {
			$data['server'] = $this->server->toArray();
		}

		return $data;
	}

}
