<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Callbacks\After;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Modifiers\FieldName;
use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ListOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\OpenAPI\Utils\SpecUtils;
use function array_values;
use function spl_object_id;

/**
 * @CreateWithoutConstructor()
 */
final class PathItem implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @FieldName("$ref")
	 * @StringValue()
	 */
	public ?string $ref = null;

	/** @StringValue() */
	public ?string $summary = null;

	/** @StringValue() */
	public ?string $description = null;

	/** @MappedObjectValue(Operation::class) */
	public ?Operation $get = null;

	/** @MappedObjectValue(Operation::class) */
	public ?Operation $put = null;

	/** @MappedObjectValue(Operation::class) */
	public ?Operation $post = null;

	/** @MappedObjectValue(Operation::class) */
	public ?Operation $delete = null;

	/** @MappedObjectValue(Operation::class) */
	public ?Operation $options = null;

	/** @MappedObjectValue(Operation::class) */
	public ?Operation $head = null;

	/** @MappedObjectValue(Operation::class) */
	public ?Operation $patch = null;

	/** @MappedObjectValue(Operation::class) */
	public ?Operation $trace = null;

	/**
	 * @var array<int, Server>
	 *
	 * @ListOf(@MappedObjectValue(Server::class))
	 * @After("afterServers")
	 */
	private array $servers = [];

	/**
	 * @var array<int, Parameter|Reference>
	 *
	 * @ListOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Parameter::class),
	 *         @MappedObjectValue(Reference::class),
	 *     })
	 * )
	 * @After("afterParameters")
	 */
	private array $parameters = [];

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
	 * @param list<Parameter|Reference> $values
	 * @return array<int, Parameter|Reference>
	 */
	protected function afterParameters(array $values): array
	{
		$parameters = [];
		foreach ($values as $value) {
			$parameters[spl_object_id($value)] = $value;
		}

		return $parameters;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [];

		if ($this->ref !== null) {
			$data['$ref'] = $this->ref;
		}

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->get !== null) {
			$data['get'] = $this->get->toRaw();
		}

		if ($this->put !== null) {
			$data['put'] = $this->put->toRaw();
		}

		if ($this->post !== null) {
			$data['post'] = $this->post->toRaw();
		}

		if ($this->delete !== null) {
			$data['delete'] = $this->delete->toRaw();
		}

		if ($this->options !== null) {
			$data['options'] = $this->options->toRaw();
		}

		if ($this->head !== null) {
			$data['head'] = $this->head->toRaw();
		}

		if ($this->patch !== null) {
			$data['patch'] = $this->patch->toRaw();
		}

		if ($this->trace !== null) {
			$data['trace'] = $this->trace->toRaw();
		}

		if ($this->servers !== []) {
			$data['servers'] = SpecUtils::specsToArray(array_values($this->servers));
		}

		if ($this->parameters !== []) {
			$data['parameters'] = SpecUtils::specsToArray(array_values($this->parameters));
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
