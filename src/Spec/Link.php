<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use ReflectionProperty;

final class Link implements SpecObject
{

	use SupportsSpecExtensions;

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

		//TODO - reference nebo id, nikdy obojí
		//		- musí však být alespoň jedna
		//TODO - reference může a nemusí existovat
		if ($this->operationRef !== null) {
			$data['operationRef'] = $this->operationRef;
		}

		//TODO - resolvnout - název operace
		if ($this->operationId !== null) {
			$data['operationId'] = $this->operationId;
		}

		//TODO - klíč je název parametru - asi z OpenAPI?
		//TODO - hodnota je raw nebo expression - jak je rozpoznat?
		if ($this->parameters !== []) {
			$data['parameters'] = $this->parameters;
		}

		//TODO - hodnota je raw nebo expression - jak je rozpoznat?
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

		$this->addExtensionsToData($data);

		return $data;
	}

}
