<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Encoding implements SpecObject
{

	public ?string $contentType;

	/** @var array<string, Header|Reference> */
	public array $headers;

	public ?string $style;

	public bool $explode;

	public bool $allowReserved;

	public function toArray(): array
	{
		return [
			'contentType' => $this->contentType,
			'headers' => SpecUtils::specsToArray($this->headers),
			'style' => $this->style,
			'explode' => $this->explode,
			'allowReserved' => $this->allowReserved,
		];
	}

}
