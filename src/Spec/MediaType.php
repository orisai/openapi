<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;

final class MediaType implements SpecObject
{

	public Schema $schema;

	/** @var mixed */
	public $example;

	/** @var array<string, Example|Reference> */
	public array $examples = [];

	/** @var array<string, Encoding> */
	public array $encoding = [];

	public function __construct()
	{
		$this->schema = new Schema();
		unset($this->example);
	}

	public function toArray(): array
	{
		$data = [];

		$schemaData = $this->schema->toArray();
		if ($schemaData !== []) {
			$data['schema'] = $schemaData;
		}

		$valueRef = new ReflectionProperty($this, 'example');
		if ($valueRef->isInitialized($this)) {
			$data['example'] = $this->example;
		}

		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		if ($this->encoding !== []) {
			$data['encoding'] = SpecUtils::specsToArray($this->encoding);
		}

		return $data;
	}

}
