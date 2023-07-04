<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\OpenAPI\Enum\SecuritySchemeType;

/**
 * @internal
 */
abstract class SecurityScheme implements SpecObject, MappedObject
{

	/** @StringValue() */
	public ?string $description = null;

	abstract public function getType(): SecuritySchemeType;

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [
			'type' => $this->getType()->value,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		return $data;
	}

}
