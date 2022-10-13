<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Paths implements SpecObject
{

	/** @var array<string, PathItem> */
	public array $paths = [];

	public function toArray(): array
	{
		return SpecUtils::specsToArray($this->paths);
	}

}
