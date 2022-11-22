<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class NullSchema extends Schema
{

	private string $type;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'null';
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['type'] = $this->type;

		return $data;
	}

}
