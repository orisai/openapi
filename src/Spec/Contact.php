<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Contact implements SpecObject
{

	public ?string $name;

	public ?string $url;

	public ?string $email;

	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'url' => $this->url,
			'email' => $this->email,
		];
	}

}
