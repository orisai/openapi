<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;

/**
 * @CreateWithoutConstructor()
 */
final class Discriminator extends MappedObject implements SpecObject
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
