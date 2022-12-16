<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Doubles;

use Orisai\OpenAPI\Spec\SpecObject;
use Orisai\OpenAPI\Spec\SpecObjectSupportsExtensions;

final class ExtendableSpecObject implements SpecObject
{

	use SpecObjectSupportsExtensions;

	public function toRaw(): array
	{
		$data = [];
		$this->addExtensionsToData($data);

		return $data;
	}

}
