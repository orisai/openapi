<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Header
{

	public ?string $description;

	public bool $required;

	public bool $deprecated;

	public bool $allowEmptyValue;

	public ?string $style;

	public bool $explode;

	public bool $allowReserved;

	public ?Schema $schema;

	/** @var mixed */
	public $example;

	/** @var array<string, Example|Reference> */
	public array $examples;

	/** @var array<string, MediaType> */
	public array $content;

}
