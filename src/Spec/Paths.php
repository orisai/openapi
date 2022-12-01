<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Attributes\Callbacks\Before;
use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use Orisai\OpenAPI\Utils\SpecUtils;
use function is_array;
use function is_string;
use function str_starts_with;

/**
 * @CreateWithoutConstructor()
 * @Before("beforeClass")
 */
final class Paths extends MappedObject implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @var array<string, PathItem>
	 *
	 * @ArrayOf(
	 *     item=@MappedObjectValue(PathItem::class),
	 *     key=@StringValue(),
	 * )
	 * @todo - callback
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

		// TODO - lépe rozlišit mezi path a extension
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
		if (!str_starts_with($path, '/')) {
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

	public function toArray(): array
	{
		$data = SpecUtils::specsToArray($this->paths);
		$this->addExtensionsToData($data);

		return $data;
	}

}
