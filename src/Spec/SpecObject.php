<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

/**
 * @internal
 */
interface SpecObject
{

	/**
	 * @return array<int|string, mixed>
	 */
	public function toArray(): array;

}
