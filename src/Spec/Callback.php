<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Attributes\Expect\AnyOf;
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
 */
final class Callback extends MappedObject implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @var array<string, PathItem|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(PathItem::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(),
	 * )
	 */
	private array $expressions = [];

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	protected static function beforeClass($data)
	{
		if (!is_array($data)) {
			return $data;
		}

		// TODO - lépe rozlišit mezi expression a extension
		$newData = [];
		foreach ($data as $key => $value) {
			if (is_string($key) && str_starts_with($key, 'x-')) {
				$newData['extensions'][$key] = $value;
			} else {
				$newData['expressions'][$key] = $value;
			}
		}

		return $newData;
	}

	/**
	 * @param PathItem|Reference $pathItem
	 */
	public function addExpression(string $expression, $pathItem): void
	{
		if (str_starts_with($expression, 'x-')) {
			$message = Message::create()
				->withContext("Adding an expression '$expression'.")
				->withProblem("Expression cannot start with 'x-' as it collides with extension names.");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->expressions[$expression] = $pathItem;
	}

	/**
	 * @return array<string, PathItem|Reference>
	 */
	public function getExpressions(): array
	{
		return $this->expressions;
	}

	public function toArray(): array
	{
		$data = SpecUtils::specsToArray($this->expressions);
		$this->addExtensionsToData($data);

		return $data;
	}

}
