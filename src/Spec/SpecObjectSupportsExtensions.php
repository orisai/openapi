<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Attributes\Callbacks\Before;
use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\MixedValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use stdClass;
use function get_debug_type;
use function is_array;
use function is_object;
use function is_resource;
use function str_starts_with;

/**
 * @internal
 * @Before("beforeClassExtensions")
 */
trait SpecObjectSupportsExtensions
{

	/**
	 * @var array<string, mixed>
	 *
	 * @ArrayOf(
	 *     item=@MixedValue(),
	 *     key=@StringValue(),
	 * )
	 */
	protected array $extensions = [];

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
	 * @return array<string, mixed>
	 */
	final public function getExtensions(): array
	{
		return $this->extensions;
	}

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	final protected function beforeClassExtensions($data)
	{
		if (!is_array($data)) {
			return $data;
		}

		//TODO - co když bude existovat klíč extensions? přidat chybu a unset?
		//TODO - přesunout x- klíče do extensions
		//TODO - zvalidovat obsah extensions (v samostatné metodě)
		return $data;
	}

	/**
	 * @param mixed $content
	 */
	private function checkExtensionContent(string $name, $content): void
	{
		if (
			(is_object($content) && !$content instanceof stdClass)
			|| is_resource($content)
		) {
			$type = get_debug_type($content);
			$message = Message::create()
				->withContext("Adding a spec extension with name '$name'.")
				->withProblem("Extension contains value of type '$type', which is not allowed.")
				->withSolution('Change value to one of supported - scalar, null, array or stdClass.');

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
