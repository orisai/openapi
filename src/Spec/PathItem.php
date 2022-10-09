<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class PathItem
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

}
