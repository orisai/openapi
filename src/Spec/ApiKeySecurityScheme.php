<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Callbacks\After;
use Orisai\ObjectMapper\Exception\ValueDoesNotMatch;
use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\MixedValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\ObjectMapper\Types\EnumType;
use Orisai\ObjectMapper\Processing\Value;
use Orisai\OpenAPI\Enum\SecuritySchemeIn;
use Orisai\OpenAPI\Enum\SecuritySchemeType;
use function is_string;

/**
 * @CreateWithoutConstructor()
 */
final class ApiKeySecurityScheme extends SecurityScheme
{

	use SpecObjectSupportsExtensions;

	/**
	 * @MixedValue()
	 * @After("afterType")
	 */
	private SecuritySchemeType $type;

	/** @StringValue() */
	public string $name;

	/**
	 * @MixedValue()
	 * @After("afterIn")
	 */
	public SecuritySchemeIn $in;

	public function __construct(string $name, SecuritySchemeIn $in)
	{
		$this->type = SecuritySchemeType::apiKey();
		$this->name = $name;
		$this->in = $in;
	}

	public function getType(): SecuritySchemeType
	{
		return $this->type;
	}

	/**
	 * @param mixed $value
	 * @throws ValueDoesNotMatch
	 */
	protected static function afterType($value): SecuritySchemeType
	{
		$case = SecuritySchemeType::apiKey();

		if ($value === $case->value) {
			return $case;
		}

		throw ValueDoesNotMatch::create(new EnumType([$case->value]), Value::of($value));
	}

	/**
	 * @param mixed $value
	 * @throws ValueDoesNotMatch
	 */
	protected static function afterIn($value): SecuritySchemeIn
	{
		if (is_string($value) && ($in = SecuritySchemeIn::tryFrom($value)) !== null) {
			return $in;
		}

		$cases = [];
		foreach (SecuritySchemeIn::cases() as $case) {
			$cases[] = $case->value;
		}

		throw ValueDoesNotMatch::create(new EnumType($cases), Value::of($value));
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['name'] = $this->name;
		$data['in'] = $this->in->value;
		$this->addExtensionsToData($data);

		return $data;
	}

}
