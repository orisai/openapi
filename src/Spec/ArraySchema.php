<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ArraySchema extends Schema
{

	private string $type;

	/** @var Schema|Reference */
	public object $items;

	/** @var int<0, max>|null */
	public ?int $minItems = null;

	/** @var int<0, max>|null */
	public ?int $maxItems = null;

	public bool $uniqueItems = false;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'array';
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['type'] = $this->type;

		if ($this->minItems !== null) {
			$data['minItems'] = $this->minItems;
		}

		if ($this->maxItems !== null) {
			$data['maxItems'] = $this->maxItems;
		}

		if ($this->uniqueItems !== false) {
			$data['uniqueItems'] = $this->uniqueItems;
		}

		return $data;
	}

}
