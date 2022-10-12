<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Utils;

use Orisai\OpenAPI\Spec\SpecObject;

final class SpecUtils
{

	/**
	 * @param array<int|string, SpecObject> $specs
	 * @return array<int|string, array<mixed>>
	 */
	public static function specsToArray(array $specs): array
	{
		$data = [];
		foreach ($specs as $key => $spec) {
			$data[$key] = $spec->toArray();
		}

		return $data;
	}

}
