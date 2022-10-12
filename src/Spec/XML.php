<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class XML implements SpecObject
{

	public ?string $name;

	public ?string $namespace;

	public ?string $prefix;

	public bool $attribute;

	public bool $wrapped;

	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'namespace' => $this->namespace,
			'prefix' => $this->prefix,
			'attribute' => $this->attribute,
			'wrapped' => $this->wrapped,
		];
	}

}
