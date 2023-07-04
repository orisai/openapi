<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\ArrayEnumValue;
use Orisai\ObjectMapper\Rules\FloatValue;

/**
 * @CreateWithoutConstructor()
 */
final class FloatSchema extends Schema
{

	/** @ArrayEnumValue({"number"}) */
	private string $type;

	/** @FloatValue() */
	public ?float $minimum = null;

	/** @FloatValue() */
	public ?float $exclusiveMinimum = null;

	/** @FloatValue() */
	public ?float $maximum = null;

	/** @FloatValue() */
	public ?float $exclusiveMaximum = null;

	/** @FloatValue() */
	public ?float $multipleOf = null;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'number';
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
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

		if ($this->multipleOf !== null) {
			$data['multipleOf'] = $this->multipleOf;
		}

		return $data;
	}

}
