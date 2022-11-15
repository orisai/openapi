<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use ReflectionProperty;

final class Schema implements SpecObject
{

	use SpecObjectChecksSerializableValue;

	public ?Discriminator $discriminator = null;

	public XML $xml;

	public ?ExternalDocumentation $externalDocs = null;

	/** @var mixed */
	private $example;

	public function __construct()
	{
		$this->xml = new XML();
		unset($this->example);
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example): void
	{
		$this->checkSerializableValue($example, 'Schema example');
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
				->withContext('Getting the Schema example.')
				->withProblem('Example is not set and so cannot be get.')
				->withSolution('Check with hasExample().');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $this->example;
	}

	public function toArray(): array
	{
		//TODO
		//	 - kompletně chybí format a description
		//		- převzaty z json schema, ve fieldech je dokumentace nezmiňuje
		//		- rozšířeny o openapi data formáty a commanmark
		//	 - i jiné fieldy jsou převzaty z json schema
		//	 - plus je uvedené, že přes $schema mohou být i custom schemata
		$data = [];

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toArray();
		}

		//TODO - pouze pro property schema, jinde nemá efekt - asi myslí Parameter?
		$xmlData = $this->xml->toArray();
		if ($xmlData !== []) {
			$data['xml'] = $xmlData;
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}

		//TODO - deprecated, json schema používá examples keyword
		if ($this->hasExample()) {
			$data['example'] = $this->example;
		}

		//TODO - objekt může mít extensions bez x- prefixu
		//		- jde o extensions z json schema nebo i o jiné?

		return $data;
	}

}
