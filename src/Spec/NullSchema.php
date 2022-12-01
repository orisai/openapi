<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\ArrayEnumValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;

/**
 * @CreateWithoutConstructor()
 */
final class NullSchema extends Schema
{

	/** @ArrayEnumValue({"null"}) */
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
