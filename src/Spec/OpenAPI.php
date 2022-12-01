<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

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
	 * @todo - after callback
	 */
	private array $servers = [];

	/** @MappedObjectValue(Paths::class) */
	public Paths $paths;

	/**
	 * @var array<string, PathItem|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(PathItem::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(),
	 * )
	 * @todo - possibly ambiguous resolving of pathitem/reference
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
	 * @todo - after callback
	 */
	private array $security = [];

	/**
	 * @var array<string, Tag>
	 *
	 * @ListOf(@MappedObjectValue(Tag::class))
	 * @todo - after callback - přidání klíčů + duplicity
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
	public function getSecurityRequirements(): array
	{
		return array_values($this->security);
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

	public function toArray(): array
	{
		$data = [
			'openapi' => $this->openapi,
			'info' => $this->info->toArray(),
		];

		if ($this->jsonSchemaDialect !== null) {
			$data['jsonSchemaDialect'] = $this->jsonSchemaDialect;
		}

		if ($this->servers !== []) {
			$data['servers'] = SpecUtils::specsToArray(array_values($this->servers));
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
			$securityByObject = [];
			foreach ($this->security as $object) {
				$securityByObject[] = $object->toArray();
			}

			$data['security'] = array_merge(...$securityByObject);
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
