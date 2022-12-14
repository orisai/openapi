<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class IntSchema extends Schema
{

	private string $type;

	public ?int $minimum = null;

	public ?int $exclusiveMinimum = null;

	public ?int $maximum = null;

	public ?int $exclusiveMaximum = null;

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
