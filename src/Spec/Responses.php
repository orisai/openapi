<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Responses implements SpecObject
{

	use SupportsSpecExtensions;

	/** @var Response|Reference|null */
	public $default;

	/** @var array<int, Response|Reference> */
	public array $responses = [];

	public function toArray(): array
	{
		//TODO - warning, když chybí úspěšná response >= 200 < 300
		//TODO - validní jsou 100-599, 1XX, 2XX, 3XX, 4XX, 5XX a default
		//TODO - ve výsledném json/yaml musí být klíč v uvozovkách
		//		- php z nich ale dělá automaticky int
		//		- json automaticky dělá string, co yaml?
		//TODO - řadit je podle specificity (becase why not) 100-199, 1XX, 200-299, ..., default
		$data = SpecUtils::specsToArray($this->responses);

		if ($this->default !== null) {
			$data['default'] = $this->default->toArray();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
