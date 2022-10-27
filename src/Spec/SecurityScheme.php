<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeType;

/**
 * @internal
 */
abstract class SecurityScheme implements SpecObject
{

	public SecuritySchemeType $type;

	public ?string $description = null;

	public function __construct(SecuritySchemeType $type)
	{
		$this->type = $type;
	}

	public function toArray(): array
	{
		//TODO - this + overrides
		$data = [
			'type' => $this->type->value,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		return $data;
	}

}
