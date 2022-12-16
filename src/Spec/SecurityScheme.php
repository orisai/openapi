<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use Orisai\OpenAPI\Enum\SecuritySchemeType;

/**
 * @internal
 * @CreateWithoutConstructor()
 */
abstract class SecurityScheme extends MappedObject implements SpecObject
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
