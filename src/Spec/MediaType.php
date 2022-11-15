<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;

final class MediaType implements SpecObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectSupportsExtensions;

	public Schema $schema;

	/** @var mixed */
	private $example;

	/** @var array<string, Example|Reference> */
	public array $examples = [];

	/** @var array<string, Encoding> */
	public array $encoding = [];

	public function __construct()
	{
		$this->schema = new Schema();
		unset($this->example);
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example): void
	{
		$this->checkSerializableValue($example, 'MediaType example');
		$this->example = $example;
	}

	public function hasExample(): bool
	{
		return (new ReflectionProperty($this, 'example'))->isInitialized($this);
	}

	/**
	 * @return mixed
	 */
	public function getExample()
	{
		if (!$this->hasExample()) {
			$message = Message::create()
				->withContext('Getting the MediaType example.')
				->withProblem('Example is not set and so cannot be get.')
				->withSolution('Check with hasExample().');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $this->example;
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
		if ($this->hasExample()) {
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
