<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Schema
{

	public ?Discriminator $discriminator;

	public ?XML $xml;

	public ?ExternalDocumentation $externalDocs;

	/** @var mixed */
	public $example;

}
