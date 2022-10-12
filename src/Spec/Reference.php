<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Reference implements SpecObject
{

	public string $ref;

	public ?string $summary;

	public string $description;

	public function toArray(): array
	{
		return [
			'$ref' => $this->ref,
			'summary' => $this->summary,
		];
	}

}
