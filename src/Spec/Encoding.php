<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Encoding
{

	public ?string $contentType;

	/** @var array<string, Header|Reference> */
	public array $headers;

	public ?string $style;

	public bool $explode;

	public bool $allowReserved;

}
