<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Callback implements SpecObject
{

	/** @var array<string, PathItem|Reference> */
	public array $expressions = [];

	public function toArray(): array
	{
		return SpecUtils::specsToArray($this->expressions);
	}

}
