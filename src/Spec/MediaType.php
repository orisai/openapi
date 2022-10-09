<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class MediaType
{

	public ?Schema $schema;

	/** @var mixed */
	public $example;

	/** @var array<string, Example|Reference> */
	public array $examples;

	/** @var array<string, Encoding> */
	public array $encoding;

}
