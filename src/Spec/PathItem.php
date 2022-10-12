<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class PathItem implements SpecObject
{

	public ?string $ref;

	public ?string $summary;

	public ?string $description;

	public ?Operation $get;

	public ?Operation $put;

	public ?Operation $post;

	public ?Operation $delete;

	public ?Operation $options;

	public ?Operation $head;

	public ?Operation $patch;

	public ?Operation $trace;

	/** @var list<Server> */
	public array $servers;

	/** @var list<Parameter|Reference>  */
	public array $parameters;

	public function toArray(): array
	{
		return [
			'$ref' => $this->ref,
			'summary' => $this->summary,
			'get' => $this->get !== null ? $this->get->toArray() : null,
			'put' => $this->put !== null ? $this->put->toArray() : null,
			'post' => $this->post !== null ? $this->post->toArray() : null,
			'delete' => $this->delete !== null ? $this->delete->toArray() : null,
			'options' => $this->options !== null ? $this->options->toArray() : null,
			'head' => $this->head !== null ? $this->head->toArray() : null,
			'patch' => $this->patch !== null ? $this->patch->toArray() : null,
			'trace' => $this->trace !== null ? $this->trace->toArray() : null,
			'servers' => SpecUtils::specsToArray($this->servers),
			'parameters' => SpecUtils::specsToArray($this->parameters),
		];
	}

}
