<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class OpenAPI
{

	/** @readonly */
	public string $openapi = '3.1.0';

	public Info $info;

	public ?string $jsonSchemaDialect;

	/** @var list<Server> */
	public array $servers;

	public ?Paths $paths;

	/** @var array<string, PathItem|Reference> */
	public array $webhooks;

	public ?Components $components;

	/** @var list<SecurityRequirement> */
	public array $security;

	/** @var list<Tag> */
	public array $tags;

	public ?ExternalDocumentation $externalDocs;

}
