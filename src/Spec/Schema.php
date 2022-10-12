<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Schema implements SpecObject
{

	public ?Discriminator $discriminator;

	public ?XML $xml;

	public ?ExternalDocumentation $externalDocs;

	/** @var mixed */
	public $example;

	public function toArray(): array
	{
		return [
			'discriminator' => $this->discriminator !== null ? $this->discriminator->toArray() : null,
			'xml' => $this->xml !== null ? $this->xml->toArray() : null,
			'externalDocs' => $this->externalDocs !== null ? $this->externalDocs->toArray() : null,
			'example' => $this->example,
		];
	}

}
