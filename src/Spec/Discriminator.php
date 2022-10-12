<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Discriminator implements SpecObject
{

	public string $propertyName;

	/** @var array<string, string> */
	public array $mapping;

	public function toArray(): array
	{
		return [
			'propertyName' => $this->propertyName,
			'mapping' => $this->mapping,
		];
	}

}
