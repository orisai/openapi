<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Example implements SpecObject
{

	public ?string $summary;

	public ?string $description;

	/** @var mixed */
	public $value;

	public ?string $externalValue;

	public function toArray(): array
	{
		return [
			'summary' => $this->summary,
			'description' => $this->description,
			'value' => $this->value,
			'externalValue' => $this->externalValue,
		];
	}

}
