<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Example
{

	public ?string $summary;

	public ?string $description;

	/** @var mixed */
	public $value;

	public ?string $externalValue;

}
