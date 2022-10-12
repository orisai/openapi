<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ServerVariable implements SpecObject
{

	/** @var non-empty-array<string>|null */
	public ?array $enum;

	public string $default;

	public ?string $description;

	public function toArray(): array
	{
		return [
			'enum' => $this->enum,
			'default' => $this->default,
			'description' => $this->description,
		];
	}

}
