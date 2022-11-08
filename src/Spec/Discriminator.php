<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Discriminator implements SpecObject
{

	use SpecObjectSupportsExtensions;

	public string $propertyName;

	/** @var array<string, string> */
	public array $mapping = [];

	public function __construct(string $propertyName)
	{
		$this->propertyName = $propertyName;
	}

	public function toArray(): array
	{
		//TODO
		$data = [
			'propertyName' => $this->propertyName,
		];

		if ($this->mapping !== []) {
			$data['mapping'] = $this->mapping;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
