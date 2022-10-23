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
		$data = SpecUtils::specsToArray($this->responses);

		if ($this->default !== null) {
			$data['default'] = $this->default->toArray();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
