<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Callbacks\Before;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ArrayEnumValue;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\IntValue;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\OpenAPI\Utils\SpecUtils;
use function in_array;
use function is_array;
use function is_int;
use function is_string;
use function ksort;
use function preg_match;
use function str_starts_with;
use const SORT_STRING;

/**
 * @Before("beforeClass")
 */
final class Responses implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @var array<int<100, 599>|string, Response|Reference>
	 * @phpstan-var array<int<100, 599>|'1XX'|'2XX'|'3XX'|'4XX'|'default', Response|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Response::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@AnyOf({
	 *         @IntValue(min=100, max=599),
	 *         @ArrayEnumValue({"1XX", "2XX", "3XX", "4XX", "default"}),
	 *     })
	 * )
	 */
	private array $responses = [];

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	protected static function beforeClass($data)
	{
		if (!is_array($data)) {
			return $data;
		}

		// TODO - kontrolovat, že ve třídě nejsou klíče extensions nebo responses
		// TODO - lépe rozlišit mezi response a extension
		//		- nebude kolidovat s before class v traitě?
		$newData = [];
		foreach ($data as $key => $value) {
			if (is_string($key) && str_starts_with($key, 'x-')) {
				$newData['extensions'][$key] = $value;
			} else {
				$newData['responses'][$key] = $value;
			}
		}

		return $newData;
	}

	/**
	 * @param int<100, 599>|string $code
	 * @param Response|Reference   $response
	 * @phpstan-param int<100, 599>|'1XX'|'2XX'|'3XX'|'4XX'|'default' $code
	 */
	public function addResponse($code, $response): void
	{
		if (is_string($code) && preg_match('#^[+-]?[0-9]+$#', $code) === 1) {
			$code = (int) $code;
		}

		if (
			/* @phpstan-ignore-next-line Intentional check of allowed */
			(is_int($code) && ($code < 100 || $code > 599))
			|| (!is_int($code) && !in_array($code, ['1XX', '2XX', '3XX', '4XX', '5XX', 'default'], true))
		) {
			$message = Message::create()
				->withContext("Adding response with code '$code'.")
				->withProblem(
					"Only codes in range 100-599, '1XX', '2XX', '3XX', '4XX', '5XX' and 'default' are allowed.",
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->responses[$code] = $response;
	}

	/**
	 * @return array<int|string, Response|Reference>
	 * @phpstan-return array<int<100, 599>|'1XX'|'2XX'|'3XX'|'4XX'|'default', Response|Reference>
	 */
	public function getResponses(): array
	{
		ksort($this->responses, SORT_STRING);

		return $this->responses;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = SpecUtils::specsToArray($this->getResponses());

		$this->addExtensionsToData($data);

		return $data;
	}

}
