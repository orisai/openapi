<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Server
{

	public string $url;

	public string $description;

	/** @var array<string, ServerVariable> */
	public array $variables;

}
