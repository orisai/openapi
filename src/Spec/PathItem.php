<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;
use function array_values;
use function spl_object_id;

final class PathItem implements SpecObject
{

	use SpecObjectSupportsExtensions;

	public ?string $ref = null;

	public ?string $summary = null;

	public ?string $description = null;

	public ?Operation $get = null;

	public ?Operation $put = null;

	public ?Operation $post = null;

	public ?Operation $delete = null;

	public ?Operation $options = null;

	public ?Operation $head = null;

	public ?Operation $patch = null;

	public ?Operation $trace = null;

	/** @var array<int, Server> */
	private array $servers = [];

	/** @var array<int, Parameter|Reference> */
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

	public function toArray(): array
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
			$data['get'] = $this->get->toArray();
		}

		if ($this->put !== null) {
			$data['put'] = $this->put->toArray();
		}

		if ($this->post !== null) {
			$data['post'] = $this->post->toArray();
		}

		if ($this->delete !== null) {
			$data['delete'] = $this->delete->toArray();
		}

		if ($this->options !== null) {
			$data['options'] = $this->options->toArray();
		}

		if ($this->head !== null) {
			$data['head'] = $this->head->toArray();
		}

		if ($this->patch !== null) {
			$data['patch'] = $this->patch->toArray();
		}

		if ($this->trace !== null) {
			$data['trace'] = $this->trace->toArray();
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
