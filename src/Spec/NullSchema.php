<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Rules\ArrayEnumValue;

final class NullSchema extends Schema
{

	/** @ArrayEnumValue({"null"}) */
	private string $type;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'null';
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
