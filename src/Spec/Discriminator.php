<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\StringValue;

/**
 * @CreateWithoutConstructor()
 */
final class Discriminator implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @readonly
	 *
	 * @StringValue()
	 */
	private string $propertyName;

	/**
	 * @var array<string, string>
	 *
	 * @ArrayOf(
	 *     item=@StringValue(),
	 *     key=@StringValue(),
	 * )
	 */
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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
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
