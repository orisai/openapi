<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class RequestBody
{

	public ?string $description;

	/** @var array<string, MediaType> */
	public array $content;

	public bool $required;

}
