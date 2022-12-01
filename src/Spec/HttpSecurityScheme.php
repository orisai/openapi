<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Attributes\Callbacks\After;
use Orisai\ObjectMapper\Attributes\Expect\MixedValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Exception\ValueDoesNotMatch;
use Orisai\ObjectMapper\Types\EnumType;
use Orisai\ObjectMapper\Types\Value;
use Orisai\OpenAPI\Enum\SecuritySchemeType;
use function strtolower;

/**
 * @CreateWithoutConstructor()
 */
final class HttpSecurityScheme extends SecurityScheme
{

	use SpecObjectSupportsExtensions;

	/**
	 * @MixedValue()
	 * @After("afterType")
	 */
	private SecuritySchemeType $type;

	/**
	 * @readonly
	 *
	 * @StringValue()
	 */
	private string $scheme;

	/** @StringValue() */
	private ?string $bearerFormat = null;

	public function __construct(string $scheme)
	{
		$this->type = SecuritySchemeType::http();
		$this->scheme = $scheme;
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
		$case = SecuritySchemeType::http();

		if ($value === $case->value) {
			return $case;
		}

		throw ValueDoesNotMatch::create(new EnumType([$case->value]), Value::of($value));
	}

	public function getScheme(): string
	{
		return $this->scheme;
	}

	public function setBearerFormat(string $format): void
	{
		if (strtolower($this->scheme) !== 'bearer') {
			$message = Message::create()
				->withContext("Setting a bearer format for security scheme '$this->scheme'.")
				->withProblem("Bearer format is supported only by scheme 'Bearer'.");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->bearerFormat = $format;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['scheme'] = $this->scheme;

		if ($this->bearerFormat !== null) {
			$data['bearerFormat'] = $this->bearerFormat;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
