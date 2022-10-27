<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use ReflectionProperty;

final class Schema implements SpecObject
{

	public ?Discriminator $discriminator = null;

	public XML $xml;

	public ?ExternalDocumentation $externalDocs = null;

	/** @var mixed */
	public $example;

	public function __construct()
	{
		$this->xml = new XML();
		unset($this->example);
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
		$valueRef = new ReflectionProperty($this, 'example');
		if ($valueRef->isInitialized($this)) {
			$data['example'] = $this->example;
		}

		//TODO - objekt může mít extensions bez x- prefixu
		//		- jde o extensions z json schema nebo i o jiné?

		return $data;
	}

}
