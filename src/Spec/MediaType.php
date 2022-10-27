<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;

final class MediaType implements SpecObject
{

	use SupportsSpecExtensions;

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
		//TODO - https://spec.openapis.org/oas/v3.1.0#considerations-for-file-uploads (a vše podtím)
		$data = [];

		$schemaData = $this->schema->toArray();
		if ($schemaData !== []) {
			$data['schema'] = $schemaData;
		}

		//TODO - pokud existuje examples, tak nesmí existovat example a naopak
		//TODO - musí matchnout media type (který zná nadřazený objekt)
		//TODO - musí matchnout schema, pokud je definované
		$valueRef = new ReflectionProperty($this, 'example');
		if ($valueRef->isInitialized($this)) {
			$data['example'] = $this->example;
		}

		//TODO - musí matchnout media type (který zná nadřazený objekt)
		//TODO - musí matchnout schema, pokud je definované
		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		//TODO - pouze pro RequestBody s typem multipart nebo application/x-www-form-urlencoded
		//		- při použití jinde a s jiným encodingem není platný
		//TODO - klíč je název property a musí existovat ve Schema
		if ($this->encoding !== []) {
			$data['encoding'] = SpecUtils::specsToArray($this->encoding);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
