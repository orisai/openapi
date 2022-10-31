<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use function get_debug_type;
use function is_array;
use function is_object;
use function is_resource;
use function str_starts_with;

/**
 * @internal
 */
trait SupportsSpecExtensions
{

	/** @var array<string, mixed> */
	private array $extensions = [];

	/**
	 * @param mixed $content
	 */
	final public function addExtension(string $name, $content): void
	{
		if (!str_starts_with($name, 'x-')) {
			$message = Message::create()
				->withContext("Adding a spec extension with name '$name'.")
				->withProblem("Extension name musts start with 'x-'");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->checkExtensionContent($name, $content);

		$this->extensions[$name] = $content;
	}

	/**
	 * @param mixed $content
	 */
	private function checkExtensionContent(string $name, $content): void
	{
		if (is_object($content) || is_resource($content)) {
			$type = get_debug_type($content);
			$message = Message::create()
				->withContext("Adding a spec extension with name '$name'.")
				->withProblem("Extension contains value of type '$type', which is not allowed.")
				->withSolution('Change value to one of supported - scalar, null or array.');

			throw InvalidArgument::create()
				->withMessage($message);
		}

		if (is_array($content)) {
			foreach ($content as $value) {
				$this->checkExtensionContent($name, $value);
			}
		}
	}

	/**
	 * @param array<mixed> $data
	 */
	private function addExtensionsToData(array &$data): void
	{
		foreach ($this->extensions as $name => $content) {
			$data[$name] = $content;
		}
	}

}
