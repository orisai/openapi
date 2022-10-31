<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;
use function array_values;
use function spl_object_id;

final class OpenAPI implements SpecObject
{

	use SupportsSpecExtensions;

	/** @readonly */
	public string $openapi;

	/** @readonly */
	public Info $info;

	public ?string $jsonSchemaDialect = null;

	/** @var array<int, Server> */
	private array $servers = [];

	/** @readonly */
	public Paths $paths;

	/** @var array<string, PathItem|Reference> */
	private array $webhooks = [];

	/** @readonly */
	public Components $components;

	/** @var array<int, SecurityRequirement> */
	private array $security = [];

	/** @var array<int, Tag> */
	private array $tags = [];

	public ?ExternalDocumentation $externalDocs = null;

	public function __construct(Info $info)
	{
		//TODO - support whole 3.1 range - ^3\\.1\\.\\d+(-.+)?$
		$this->openapi = '3.1.0';
		$this->info = $info;
		$this->components = new Components();
		$this->paths = new Paths();
	}

	public function addServer(Server $server): void
	{
		$this->servers[spl_object_id($server)] = $server;
	}

	/**
	 * @return list<Server>
	 */
	public function getServers(): array
	{
		return array_values($this->servers);
	}

	/**
	 * @param PathItem|Reference $webhook
	 */
	public function addWebhook(string $key, $webhook): void
	{
		$this->webhooks[$key] = $webhook;
	}

	/**
	 * @return array<string, PathItem|Reference>
	 */
	public function getWebhooks(): array
	{
		return $this->webhooks;
	}

	public function addSecurityRequirement(SecurityRequirement $requirement): void
	{
		$this->security[spl_object_id($requirement)] = $requirement;
	}

	/**
	 * @return list<SecurityRequirement>
	 */
	public function getSecurityRequirements(): array
	{
		return array_values($this->security);
	}

	public function addTag(Tag $tag): void
	{
		$this->tags[spl_object_id($tag)] = $tag;
	}

	/**
	 * @return list<Tag>
	 */
	public function getTags(): array
	{
		return array_values($this->tags);
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

		//TODO - nevkládat? neukládáme defaults
		$servers = $this->servers === []
			? [new Server('/')]
			: array_values($this->servers);
		$data['servers'] = SpecUtils::specsToArray($servers);

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
			$data['security'] = SpecUtils::specsToArray(array_values($this->security));
		}

		if ($this->tags !== []) {
			$data['tags'] = SpecUtils::specsToArray(array_values($this->tags));
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
