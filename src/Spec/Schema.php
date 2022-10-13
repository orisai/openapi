<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use ReflectionProperty;

final class Schema implements SpecObject
{

	public ?Discriminator $discriminator = null;

	public XML $xml;

	public ?ExternalDocumentation $externalDocs = null;

	/** @var mixed */
	public $example;

	public function __construct()
	{
		$this->xml = new XML();
		unset($this->example);
	}

	public function toArray(): array
	{
		$data = [];

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toArray();
		}

		$xmlData = $this->xml->toArray();
		if ($xmlData !== []) {
			$data['xml'] = $xmlData;
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}

		$valueRef = new ReflectionProperty($this, 'example');
		if ($valueRef->isInitialized($this)) {
			$data['example'] = $this->example;
		}

		return $data;
	}

}
