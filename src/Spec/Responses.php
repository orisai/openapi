<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use function in_array;
use function is_int;
use function ksort;

final class Responses implements SpecObject
{

	use SupportsSpecExtensions;

	/**
	 * @var array<int<100, 599>|string, Response|Reference>
	 * @phpstan-var array<int<100, 599>|'1XX'|'2XX'|'3XX'|'4XX'|'default', Response|Reference>
	 */
	private array $responses = [];

	/**
	 * @param int<100, 599>|string $code
	 * @param Response|Reference $response
	 * @phpstan-param int<100, 599>|'1XX'|'2XX'|'3XX'|'4XX'|'default' $code
	 */
	public function addResponse($code, $response): void
	{
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

	public function toArray(): array
	{
		//TODO - ve výsledném json/yaml musí být klíč v uvozovkách
		//		- php z nich ale dělá automaticky int
		//		- json automaticky dělá string, co yaml?
		$data = SpecUtils::specsToArray($this->getSortedResponses());

		$this->addExtensionsToData($data);

		return $data;
	}

	/**
	 * @return array<int|string, Response|Reference>
	 * @phpstan-return array<int<100, 599>|'1XX'|'2XX'|'3XX'|'4XX'|'default', Response|Reference>
	 */
	private function getSortedResponses(): array
	{
		$grouped = [
			1 => [],
			'1XX' => [],
			2 => [],
			'2XX' => [],
			3 => [],
			'3XX' => [],
			4 => [],
			'4XX' => [],
			5 => [],
			'5XX' => [],
			'default' => [],
		];

		foreach ($this->responses as $code => $response) {
			if (isset($grouped[$code])) {
				$grouped[$code][$code] = $response;
			} elseif ($code <= 199) {
				$grouped[1][$code] = $response;
			} elseif ($code <= 299) {
				$grouped[2][$code] = $response;
			} elseif ($code <= 399) {
				$grouped[3][$code] = $response;
			} elseif ($code <= 499) {
				$grouped[4][$code] = $response;
			} else {
				$grouped[5][$code] = $response;
			}
		}

		$responses = [];
		foreach ($grouped as $group) {
			ksort($group);
			foreach ($group as $code => $response) {
				$responses[$code] = $response;
			}
		}

		return $responses;
	}

}
