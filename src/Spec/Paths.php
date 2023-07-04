<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Callbacks\After;
use Orisai\ObjectMapper\Callbacks\Before;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\OpenAPI\Utils\SpecUtils;
use function is_array;
use function is_string;
use function str_starts_with;

/**
 * @Before("beforeClass")
 */
final class Paths implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @var array<string, PathItem>
	 *
	 * @ArrayOf(
	 *     item=@MappedObjectValue(PathItem::class),
	 *     key=@StringValue(),
	 * )
	 * @After("afterPaths")
	 */
	private array $paths = [];

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	protected static function beforeClass($data)
	{
		if (!is_array($data)) {
			return $data;
		}

		// TODO - kontrolovat, že ve třídě nejsou klíče extensions nebo paths
		// TODO - lépe rozlišit mezi path a extension
		//		- nebude kolidovat s before class v traitě?
		$newData = [];
		foreach ($data as $key => $value) {
			if (is_string($key) && str_starts_with($key, 'x-')) {
				$newData['extensions'][$key] = $value;
			} else {
				$newData['paths'][$key] = $value;
			}
		}

		return $newData;
	}

	public function addPath(string $path, PathItem $item): void
	{
		if (!$this->isPathValid($path)) {
			$message = Message::create()
				->withContext("Adding path '$path'.")
				->withProblem("Path musts start with '/'.");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->paths[$path] = $item;
	}

	/**
	 * @return array<string, PathItem>
	 */
	public function getPaths(): array
	{
		return $this->paths;
	}

	private function isPathValid(string $path): bool
	{
		return str_starts_with($path, '/');
	}

	/**
	 * @param array<string, PathItem> $values
	 */
	protected function afterPaths(array $values): void
	{
		foreach ($values as $path => $item) {
			if (!$this->isPathValid($path)) {
				// TODO - validate path
			}
		}
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = SpecUtils::specsToArray($this->paths);
		$this->addExtensionsToData($data);

		return $data;
	}

}
