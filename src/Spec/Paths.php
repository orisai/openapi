<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Paths implements SpecObject
{

	use SupportsSpecExtensions;

	/** @var array<string, PathItem> */
	public array $paths = [];

	public function toArray(): array
	{
		//TODO - path musí začínat /
		//TODO - validovat templates {param}
		//TODO - validovat kolize
		$data = SpecUtils::specsToArray($this->paths);
		$this->addExtensionsToData($data);

		return $data;
	}

}
