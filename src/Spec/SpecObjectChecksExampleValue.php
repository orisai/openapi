<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use stdClass;
use function get_debug_type;
use function is_array;
use function is_object;
use function is_resource;

/**
 * @internal
 */
trait SpecObjectChecksExampleValue
{

	/**
	 * @param mixed $content
	 */
	private function checkExampleValue($content): void
	{
		if (
			(is_object($content) && !$content instanceof stdClass)
			|| is_resource($content)
		) {
			$type = get_debug_type($content);
			$message = Message::create()
				->withContext('Setting an example.')
				->withProblem("Value contains type '$type', which is not allowed.")
				->withSolution('Change type to one of supported - scalar, null, array or stdClass.');

			throw InvalidArgument::create()
				->withMessage($message);
		}

		if (is_array($content)) {
			foreach ($content as $value) {
				$this->checkExampleValue($value);
			}
		}
	}

}
