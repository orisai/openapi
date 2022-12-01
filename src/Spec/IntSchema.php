<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\ArrayEnumValue;
use Orisai\ObjectMapper\Attributes\Expect\IntValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;

/**
 * @CreateWithoutConstructor()
 */
final class IntSchema extends Schema
{

	/** @ArrayEnumValue({"integer"}) */
	private string $type;

	/** @IntValue() */
	public ?int $minimum = null;

	/** @IntValue() */
	public ?int $exclusiveMinimum = null;

	/** @IntValue() */
	public ?int $maximum = null;

	/** @IntValue() */
	public ?int $exclusiveMaximum = null;

	/** @IntValue() */
	public int $multipleOf = 1;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'integer';
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['type'] = $this->type;

		if ($this->minimum !== null) {
			$data['minimum'] = $this->minimum;
		}

		if ($this->exclusiveMinimum !== null) {
			$data['exclusiveMinimum'] = $this->exclusiveMinimum;
		}

		if ($this->maximum !== null) {
			$data['maximum'] = $this->maximum;
		}

		if ($this->exclusiveMaximum !== null) {
			$data['exclusiveMaximum'] = $this->exclusiveMaximum;
		}

		if ($this->multipleOf !== 1) {
			$data['multipleOf'] = $this->multipleOf;
		}

		return $data;
	}

}
