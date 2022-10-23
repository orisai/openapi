<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Encoding implements SpecObject
{

	public ?string $contentType = null;

	/** @var array<string, Header|Reference> */
	public array $headers = [];

	public ?string $style = null;

	public bool $explode = false;

	public bool $allowReserved = false;

	public function toArray(): array
	{
		$data = [];

		if ($this->contentType !== null) {
			$data['contentType'] = $this->contentType;
		}

		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		if ($this->style !== null) {
			$data['style'] = $this->style;
		}

		if ($this->explode) {
			$data['explode'] = $this->explode;
		}

		if ($this->allowReserved) {
			$data['allowReserved'] = $this->allowReserved;
		}

		return $data;
	}

}
