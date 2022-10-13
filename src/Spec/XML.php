<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class XML implements SpecObject
{

	public ?string $name = null;

	public ?string $namespace = null;

	public ?string $prefix = null;

	public bool $attribute = false;

	public bool $wrapped = false;

	public function toArray(): array
	{
		$data = [];

		if ($this->name !== null) {
			$data['name'] = $this->name;
		}

		if ($this->namespace !== null) {
			$data['namespace'] = $this->namespace;
		}

		if ($this->prefix !== null) {
			$data['prefix'] = $this->prefix;
		}

		if ($this->attribute) {
			$data['attribute'] = $this->attribute;
		}

		if ($this->wrapped) {
			$data['wrapped'] = $this->wrapped;
		}

		return $data;
	}

}
