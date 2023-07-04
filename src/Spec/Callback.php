<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\OpenAPI\Utils\SpecUtils;
use function is_array;
use function is_string;
use function str_starts_with;

final class Callback implements SpecObject, MappedObject
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

		// TODO - kontrolovat, že ve třídě nejsou klíče extensions nebo expressions
		// TODO - lépe rozlišit mezi expression a extension
		//		- nebude kolidovat s before class v traitě?
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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = SpecUtils::specsToArray($this->expressions);
		$this->addExtensionsToData($data);

		return $data;
	}

}
