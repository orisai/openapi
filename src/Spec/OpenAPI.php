<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Callbacks\After;
use Orisai\ObjectMapper\Attributes\Expect\AnyOf;
use Orisai\ObjectMapper\Attributes\Expect\ArrayEnumValue;
use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\ListOf;
use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use Orisai\OpenAPI\Utils\SpecUtils;
use function array_merge;
use function array_values;
use function spl_object_id;

/**
 * @CreateWithoutConstructor()
 */
final class OpenAPI extends MappedObject implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @readonly
	 *
	 * @ArrayEnumValue({"3.1.0"})
	 */
	private string $openapi;

	/**
	 * @readonly
	 *
	 * @MappedObjectValue(Info::class)
	 */
	private Info $info;

	/** @StringValue() */
	public ?string $jsonSchemaDialect = null;

	/**
	 * @var array<int, Server>
	 *
	 * @ListOf(@MappedObjectValue(Server::class))
	 * @After("afterServers")
	 */
	private array $servers = [];

	/** @MappedObjectValue(Paths::class) */
	public Paths $paths;

	/**
	 * @var array<string, PathItem|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Reference::class),
	 *         @MappedObjectValue(PathItem::class),
	 *     }),
	 *     key=@StringValue(),
	 * )
	 */
	private array $webhooks = [];

	/**
	 * @readonly
	 *
	 * @MappedObjectValue(Components::class)
	 */
	public Components $components;

	/**
	 * @var array<int, SecurityRequirement>
	 *
	 * @ListOf(@MappedObjectValue(SecurityRequirement::class))
	 * @After("afterSecurity")
	 */
	private array $security = [];

	/**
	 * @var array<string, Tag>
	 *
	 * @ListOf(@MappedObjectValue(Tag::class))
	 * @After("afterTags")
	 */
	private array $tags = [];

	/** @MappedObjectValue(ExternalDocumentation::class) */
	public ?ExternalDocumentation $externalDocs = null;

	public function __construct(Info $info)
	{
		$this->openapi = '3.1.0';
		$this->info = $info;
		$this->components = new Components();
		$this->paths = new Paths();
	}

	public function getOpenapiVersion(): string
	{
		return $this->openapi;
	}

	public function getInfo(): Info
	{
		return $this->info;
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
	 * @param list<Server> $values
	 * @return array<int, Server>
	 */
	protected function afterServers(array $values): array
	{
		$servers = [];
		foreach ($values as $value) {
			$servers[spl_object_id($value)] = $value;
		}

		return $servers;
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

	public function addSecurity(SecurityRequirement $requirement): void
	{
		$this->security[spl_object_id($requirement)] = $requirement;
	}

	/**
	 * @return list<SecurityRequirement>
	 */
	public function getSecurity(): array
	{
		return array_values($this->security);
	}

	/**
	 * @param list<SecurityRequirement> $values
	 * @return array<int, SecurityRequirement>
	 */
	protected function afterSecurity(array $values): array
	{
		$security = [];
		foreach ($values as $value) {
			$security[spl_object_id($value)] = $value;
		}

		return $security;
	}

	public function addTag(Tag $tag): void
	{
		$this->tags[$tag->getName()] = $tag;
	}

	/**
	 * @return list<Tag>
	 */
	public function getTags(): array
	{
		return array_values($this->tags);
	}

	/**
	 * @param list<Tag> $values
	 * @return array<string, Tag>
	 */
	protected function afterTags(array $values): array
	{
		// TODO - duplicates
		$tags = [];
		foreach ($values as $value) {
			$tags[$value->getName()] = $value;
		}

		return $tags;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [
			'openapi' => $this->openapi,
			'info' => $this->info->toRaw(),
		];

		if ($this->jsonSchemaDialect !== null) {
			$data['jsonSchemaDialect'] = $this->jsonSchemaDialect;
		}

		if ($this->servers !== []) {
			$data['servers'] = SpecUtils::specsToArray(array_values($this->servers));
		}

		$pathsData = $this->paths->toRaw();
		if ($pathsData !== []) {
			$data['paths'] = $pathsData;
		}

		if ($this->webhooks !== []) {
			$data['webhooks'] = SpecUtils::specsToArray($this->webhooks);
		}

		$componentsData = $this->components->toRaw();
		if ($componentsData !== []) {
			$data['components'] = $componentsData;
		}

		if ($this->security !== []) {
			$securityByObject = [];
			foreach ($this->security as $object) {
				$securityByObject[] = $object->toRaw();
			}

			$data['security'] = array_merge(...$securityByObject);
		}

		if ($this->tags !== []) {
			$data['tags'] = SpecUtils::specsToArray(array_values($this->tags));
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toRaw();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
