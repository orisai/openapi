<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use function str_starts_with;

final class Callback implements SpecObject
{

	use SupportsSpecExtensions;

	/** @var array<string, PathItem|Reference> */
	private array $expressions = [];

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
