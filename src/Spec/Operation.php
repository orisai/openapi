<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\AnyOf;
use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\BoolValue;
use Orisai\ObjectMapper\Attributes\Expect\ListOf;
use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use Orisai\OpenAPI\Utils\SpecUtils;
use function array_merge;
use function array_values;
use function in_array;
use function spl_object_id;

/**
 * @CreateWithoutConstructor()
 */
final class Operation extends MappedObject implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @var list<string>
	 *
	 * @ListOf(@StringValue())
	 * @todo - duplicity
	 */
	private array $tags = [];

	/** @StringValue() */
	public ?string $summary = null;

	/** @StringValue() */
	public ?string $description = null;

	/** @MappedObjectValue(ExternalDocumentation::class) */
	public ?ExternalDocumentation $externalDocs = null;

	/** @StringValue() */
	public ?string $operationId = null;

	/**
	 * @var array<int, Parameter|Reference>
	 *
	 * @ListOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Parameter::class),
	 *         @MappedObjectValue(Reference::class),
	 *     })
	 * )
	 * @todo - after callback
	 */
	private array $parameters = [];

	/**
	 * @var RequestBody|Reference|null
	 *
	 * @AnyOf({
	 *     @MappedObjectValue(RequestBody::class),
	 *     @MappedObjectValue(Reference::class),
	 * })
	 * @todo - nemá pravidlo pro null, takže je podle object mapperu required
	 *          - stači přidat typ ?object, ale lepší bude defaults předělat v object mapperu - žádná detekce nullable podle pravidel
	 */
	public ?object $requestBody = null;

	/** @MappedObjectValue(Responses::class) */
	public Responses $responses;

	/**
	 * @var array<string, Callback|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Callback::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(),
	 * )
	 */
	private array $callbacks = [];

	/** @BoolValue() */
	public bool $deprecated = false;

	/**
	 * @var array<int, SecurityRequirement>
	 *
	 * @ListOf(@MappedObjectValue(SecurityRequirement::class))
	 * @todo - after callback
	 */
	private array $security = [];

	/**
	 * @var array<int, Server>
	 *
	 * @ListOf(@MappedObjectValue(Server::class))
	 * @todo - after callback
	 */
	private array $servers = [];

	public function __construct()
	{
		$this->responses = new Responses();
	}

	public function addTag(string $tag): void
	{
		if (in_array($tag, $this->tags, true)) {
			return;
		}

		$this->tags[] = $tag;
	}

	/**
	 * @return list<string>
	 */
	public function getTags(): array
	{
		return $this->tags;
	}

	/**
	 * @param Parameter|Reference $parameter
	 */
	public function addParameter($parameter): void
	{
		$this->parameters[spl_object_id($parameter)] = $parameter;
	}

	/**
	 * @return list<Parameter|Reference>
	 */
	public function getParameters(): array
	{
		return array_values($this->parameters);
	}

	/**
	 * @param Callback|Reference $callback
	 */
	public function addCallback(string $key, $callback): void
	{
		$this->callbacks[$key] = $callback;
	}

	/**
	 * @return array<string, Callback|Reference>
	 */
	public function getCallbacks(): array
	{
		return $this->callbacks;
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

	public function toArray(): array
	{
		$data = [];

		$tags = $this->getTags();
		if ($tags !== []) {
			$data['tags'] = $tags;
		}

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}

		if ($this->operationId !== null) {
			$data['operationId'] = $this->operationId;
		}

		if ($this->parameters !== []) {
			$data['parameters'] = SpecUtils::specsToArray(array_values($this->parameters));
		}

		if ($this->requestBody !== null) {
			$data['requestBody'] = $this->requestBody->toArray();
		}

		$responsesData = $this->responses->toArray();
		if ($responsesData !== []) {
			$data['responses'] = $responsesData;
		}

		if ($this->callbacks !== []) {
			$data['callbacks'] = SpecUtils::specsToArray($this->callbacks);
		}

		if ($this->deprecated) {
			$data['deprecated'] = $this->deprecated;
		}

		if ($this->security !== []) {
			$securityByObject = [];
			foreach ($this->security as $object) {
				$securityByObject[] = $object->toArray();
			}

			$data['security'] = array_merge(...$securityByObject);
		}

		if ($this->servers !== []) {
			$data['servers'] = SpecUtils::specsToArray(array_values($this->servers));
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
