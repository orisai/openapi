<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Callbacks\After;
use Orisai\ObjectMapper\Exception\ValueDoesNotMatch;
use Orisai\ObjectMapper\Processing\Value;
use Orisai\ObjectMapper\Rules\MixedValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\ObjectMapper\Types\EnumType;
use Orisai\OpenAPI\Enum\SecuritySchemeType;

final class OpenIDConnectSecurityScheme extends SecurityScheme
{

	use SpecObjectSupportsExtensions;

	/**
	 * @MixedValue()
	 * @After("afterType")
	 */
	private SecuritySchemeType $type;

	/** @StringValue() */
	public string $openIdConnectUrl;

	public function __construct(string $openIdConnectUrl)
	{
		$this->type = SecuritySchemeType::openIdConnect();
		$this->openIdConnectUrl = $openIdConnectUrl;
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
		$case = SecuritySchemeType::openIdConnect();

		if ($value === $case->value) {
			return $case;
		}

		throw ValueDoesNotMatch::create(new EnumType([$case->value]), Value::of($value));
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['openIdConnectUrl'] = $this->openIdConnectUrl;

		$this->addExtensionsToData($data);

		return $data;
	}

}
