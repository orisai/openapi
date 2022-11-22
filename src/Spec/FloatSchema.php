<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class FloatSchema extends Schema
{

	private string $type;

	public ?float $minimum = null;

	public ?float $exclusiveMinimum = null;

	public ?float $maximum = null;

	public ?float $exclusiveMaximum = null;

	public ?float $multipleOf = null;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'number';
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

		if ($this->multipleOf !== null) {
			$data['multipleOf'] = $this->multipleOf;
		}

		return $data;
	}

}
