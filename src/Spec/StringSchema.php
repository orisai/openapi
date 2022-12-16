<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\ArrayEnumValue;
use Orisai\ObjectMapper\Attributes\Expect\IntValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;

/**
 * @CreateWithoutConstructor()
 */
final class StringSchema extends Schema
{

	/** @ArrayEnumValue({"string"}) */
	private string $type;

	/**
	 * @var int<0, max>|null
	 *
	 * @IntValue(min=0)
	 */
	public ?int $minLength = null;

	/**
	 * @var int<0, max>|null
	 *
	 * @IntValue(min=0)
	 */
	public ?int $maxLength = null;

	/** @StringValue() */
	private ?string $pattern = null;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'string';
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
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
