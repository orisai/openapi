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
		$data = SpecUtils::specsToArray($this->expressions);
		$this->addExtensionsToData($data);

		return $data;
	}

}
