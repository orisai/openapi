<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Responses implements SpecObject
{

	use SupportsSpecExtensions;

	/** @var array<int<100, 599>|'1XX'|'2XX'|'3XX'|'4XX'|'default', Response|Reference> */
	private array $responses = [];

	/**
	 * @param int<100, 599>|'1XX'|'2XX'|'3XX'|'4XX'|'default' $code
	 * @param Response|Reference $response
	 */
	public function addResponse($code, $response): void
	{
		// TODO - validovat kódy
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

		//TODO - tests
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
			foreach ($group as $code => $response) {
				$responses[$code] = $response;
			}
		}

		return $responses;
	}

}
