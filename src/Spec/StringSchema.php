<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class StringSchema extends Schema
{

	private string $type;

	/** @var int<0, max>|null */
	public ?int $minLength = null;

	/** @var int<0, max>|null */
	public ?int $maxLength = null;

	private ?string $pattern = null;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'string';
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['type'] = $this->type;

		if ($this->minLength !== null) {
			$data['minLength'] = $this->minLength;
		}

		if ($this->maxLength !== null) {
			$data['maxLength'] = $this->maxLength;
		}

		if ($this->pattern !== null) {
			$data['pattern'] = $this->pattern;
		}

		return $data;
	}

}
