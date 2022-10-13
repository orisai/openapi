<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use ReflectionProperty;

final class Example implements SpecObject
{

	public ?string $summary = null;

	public ?string $description = null;

	/** @var mixed */
	public $value;

	public ?string $externalValue = null;

	public function __construct()
	{
		unset($this->value);
	}

	public function toArray(): array
	{
		$data = [];

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		$valueRef = new ReflectionProperty($this, 'value');
		if ($valueRef->isInitialized($this)) {
			$data['value'] = $this->value;
		}

		if ($this->externalValue !== null) {
			$data['externalValue'] = $this->externalValue;
		}

		return $data;
	}

}
