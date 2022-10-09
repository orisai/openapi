<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Response
{

	public string $description;

	/** @var array<string, Header|Reference> */
	public array $headers;

	/** @var array<string, MediaType> */
	public array $content;

	/** @var array<string, Link|Reference> */
	public array $links;

}
