<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class OpenAPI implements SpecObject
{

	/** @readonly */
	public string $openapi;

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

	public function __construct()
	{
		$this->openapi = '3.1.0';
	}

	public function toArray(): array
	{
		return [
			'openapi' => $this->openapi,
			'info' => $this->info->toArray(),
			'jsonSchemaDialect' => $this->jsonSchemaDialect,
			'servers' => SpecUtils::specsToArray($this->servers),
			'paths' => $this->paths !== null ? $this->paths->toArray() : null,
			'webhooks' => SpecUtils::specsToArray($this->webhooks),
			'components' => $this->components !== null ? $this->components->toArray() : null,
			'security' => SpecUtils::specsToArray($this->security),
			'tags' => SpecUtils::specsToArray($this->tags),
			'externalDocs' => $this->externalDocs !== null ? $this->externalDocs->toArray() : null,
		];
	}

}
