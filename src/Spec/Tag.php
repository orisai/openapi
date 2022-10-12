<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Tag implements SpecObject
{

	public string $name;

	public ?string $description;

	public ?ExternalDocumentation $externalDocs;

	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'description' => $this->description,
			'externalDocs' => $this->externalDocs !== null ? $this->externalDocs->toArray() : null,
		];
	}

}
