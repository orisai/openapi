<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Components
{

	/** @var array<string, Schema|Reference> */
	public array $schemas;

	/** @var array<string, Response|Reference> */
	public array $responses;

	/** @var array<string, Parameter|Reference> */
	public array $parameters;

	/** @var array<string, Example|Reference> */
	public array $examples;

	/** @var array<string, RequestBody|Reference> */
	public array $requestBodies;

	/** @var array<string, Header|Reference> */
	public array $headers;

	/** @var array<string, SecurityScheme|Reference> */
	public array $securitySchemes;

	/** @var array<string, Link|Reference> */
	public array $links;

	/** @var array<string, Callback|Reference> */
	public array $callbacks;

	/** @var array<string, PathItem|Reference> */
	public array $pathItems;

}
