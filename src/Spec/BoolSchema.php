<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Rules\ArrayEnumValue;

final class BoolSchema extends Schema
{

	/** @ArrayEnumValue({"boolean"}) */
	private string $type;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'boolean';
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['type'] = $this->type;

		return $data;
	}

}
