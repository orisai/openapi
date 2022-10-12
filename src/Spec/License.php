<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class License implements SpecObject
{

	public string $name;

	public ?string $identifier;

	public ?string $url;

	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'identifier' => $this->identifier,
			'url' => $this->url,
		];
	}

}
