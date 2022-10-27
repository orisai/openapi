<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Callback implements SpecObject
{

	use SupportsSpecExtensions;

	/** @var array<string, PathItem|Reference> */
	public array $expressions = [];

	public function toArray(): array
	{
		//TODO - validovat expressions?
		// https://spec.openapis.org/oas/v3.1.0#callback-object
		$data = SpecUtils::specsToArray($this->expressions);
		$this->addExtensionsToData($data);

		return $data;
	}

}
