<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeType;

/**
 * @internal
 */
abstract class SecurityScheme implements SpecObject
{

	private SecuritySchemeType $type;

	public ?string $description = null;

	protected function __construct(SecuritySchemeType $type)
	{
		$this->type = $type;
	}

	public function getType(): SecuritySchemeType
	{
		return $this->type;
	}

	public function toArray(): array
	{
		$data = [
			'type' => $this->type->value,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		return $data;
	}

}
