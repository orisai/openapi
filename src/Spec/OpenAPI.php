<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class OpenAPI implements SpecObject
{

	use SupportsSpecExtensions;

	/** @readonly */
	public string $openapi;

	public Info $info;

	public ?string $jsonSchemaDialect = null;

	/** @var list<Server> */
	public array $servers = [];

	public Paths $paths;

	/** @var array<string, PathItem|Reference> */
	public array $webhooks = [];

	public Components $components;

	/** @var list<SecurityRequirement> */
	public array $security = [];

	/** @var list<Tag> */
	public array $tags = [];

	public ?ExternalDocumentation $externalDocs = null;

	public function __construct(Info $info)
	{
		$this->openapi = '3.1.0';
		$this->info = $info;
		$this->components = new Components();
		$this->paths = new Paths();
	}

	public function toArray(): array
	{
		$data = [
			'openapi' => $this->openapi,
			'info' => $this->info->toArray(),
		];

		if ($this->jsonSchemaDialect !== null) {
			$data['jsonSchemaDialect'] = $this->jsonSchemaDialect;
		}

		//TODO - prázdné servery vrátí Server('/')
		if ($this->servers !== []) {
			$data['servers'] = SpecUtils::specsToArray($this->servers);
		}

		$pathsData = $this->paths->toArray();
		if ($pathsData !== []) {
			$data['paths'] = $pathsData;
		}

		if ($this->webhooks !== []) {
			$data['webhooks'] = SpecUtils::specsToArray($this->webhooks);
		}

		$componentsData = $this->components->toArray();
		if ($componentsData !== []) {
			$data['components'] = $componentsData;
		}

		if ($this->security !== []) {
			$data['security'] = SpecUtils::specsToArray($this->security);
		}

		//TODO - každý tag musí být unikátní
		if ($this->tags !== []) {
			$data['tags'] = SpecUtils::specsToArray($this->tags);
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
