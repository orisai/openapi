<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

/**
 * @internal
 */
interface SpecObject
{

	/**
	 * @return mixed
	 */
	public function toRaw();

}
