<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Callbacks\After;
use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Expect\MixedValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Exception\ValueDoesNotMatch;
use Orisai\ObjectMapper\Types\EnumType;
use Orisai\ObjectMapper\Types\Value;
use Orisai\OpenAPI\Enum\SecuritySchemeType;

/**
 * @CreateWithoutConstructor()
 */
final class OAuthSecurityScheme extends SecurityScheme
{

	use SpecObjectSupportsExtensions;

	/**
	 * @MixedValue()
	 * @After("afterType")
	 */
	private SecuritySchemeType $type;

	/**
	 * @MappedObjectValue(OAuthFlows::class)
	 * @todo - flows jsou required, object mapper je vytvoří automaticky
	 *       - měly by být readonly
	 */
	public OAuthFlows $flows;

	public function __construct(OAuthFlows $flows)
	{
		$this->type = SecuritySchemeType::oAuth2();
		$this->flows = $flows;
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
		$case = SecuritySchemeType::oAuth2();

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
		$data['flows'] = $this->flows->toRaw();

		$this->addExtensionsToData($data);

		return $data;
	}

}
