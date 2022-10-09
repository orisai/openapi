<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ServerVariable
{

	/** @var non-empty-array<string>|null */
	public ?array $enum;

	public string $default;

	public ?string $description;

}
