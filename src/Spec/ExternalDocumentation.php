<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ExternalDocumentation implements SpecObject
{

	public ?string $description;

	public string $url;

	public function toArray(): array
	{
		return [
			'description' => $this->description,
			'url' => $this->url,
		];
	}

}
