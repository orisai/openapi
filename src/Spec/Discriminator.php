<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Discriminator
{

	public string $propertyName;

	/** @var array<string, string> */
	public array $mapping;

}
