<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class SecurityRequirement implements SpecObject
{

	/** @var array<string, list<string>> */
	public array $requirements;

	public function toArray(): array
	{
		return $this->requirements;
	}

}
