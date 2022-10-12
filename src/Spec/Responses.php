<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Responses implements SpecObject
{

	/** @var Response|Reference|null */
	public $default;

	/** @var array<int, Response|Reference> */
	public array $responses;

	public function toArray(): array
	{
		$data = SpecUtils::specsToArray($this->responses);
		$data['default'] = $this->default !== null ? $this->default->toArray() : null;

		return $data;
	}

}
