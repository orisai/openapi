<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Doubles;

use Orisai\OpenAPI\Spec\SpecObject;
use Orisai\OpenAPI\Spec\SupportsSpecExtensions;

final class ExtendableSpecObject implements SpecObject
{

	use SupportsSpecExtensions;

	public function toArray(): array
	{
		$data = [];
		$this->addExtensionsToData($data);

		return $data;
	}

}
