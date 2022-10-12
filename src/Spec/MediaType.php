<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class MediaType implements SpecObject
{

	public ?Schema $schema;

	/** @var mixed */
	public $example;

	/** @var array<string, Example|Reference> */
	public array $examples;

	/** @var array<string, Encoding> */
	public array $encoding;

	public function toArray(): array
	{
		return [
			'schema' => $this->schema !== null ? $this->schema->toArray() : null,
			'example' => $this->example,
			'examples' => SpecUtils::specsToArray($this->examples),
			'encoding' => SpecUtils::specsToArray($this->encoding),
		];
	}

}
