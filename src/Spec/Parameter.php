<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Parameter implements SpecObject
{

	public string $name;

	public string $in;

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

	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'in' => $this->in,
			'description' => $this->description,
			'required' => $this->required,
			'deprecated' => $this->deprecated,
			'allowEmptyValue' => $this->allowEmptyValue,
			'style' => $this->style,
			'explode' => $this->explode,
			'allowReserved' => $this->allowReserved,
			'schema' => $this->schema !== null ? $this->schema->toArray() : null,
			'example' => $this->example,
			'examples' => SpecUtils::specsToArray($this->examples),
			'content' => SpecUtils::specsToArray($this->content),
		];
	}

}
