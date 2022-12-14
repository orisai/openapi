<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Discriminator implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/** @readonly  */
	private string $propertyName;

	/** @var array<string, string> */
	private array $mapping = [];

	public function __construct(string $propertyName)
	{
		$this->propertyName = $propertyName;
	}

	public function getPropertyName(): string
	{
		return $this->propertyName;
	}

	public function addMapping(string $value, string $schema): void
	{
		$this->mapping[$value] = $schema;
	}

	/**
	 * @return array<string, string>
	 */
	public function getMapping(): array
	{
		return $this->mapping;
	}

	public function toArray(): array
	{
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
