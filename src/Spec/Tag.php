<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\StringValue;

final class Tag implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @readonly
	 *
	 * @StringValue()
	 */
	private string $name;

	/** @StringValue() */
	public ?string $description = null;

	/** @MappedObjectValue(ExternalDocumentation::class) */
	public ?ExternalDocumentation $externalDocs = null;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [
			'name' => $this->name,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toRaw();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
