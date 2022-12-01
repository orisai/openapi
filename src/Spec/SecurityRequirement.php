<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\ListOf;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use stdClass;

/**
 * @CreateWithoutConstructor()
 */
final class SecurityRequirement extends MappedObject implements SpecObject
{

	/**
	 * @readonly
	 *
	 * @StringValue()
	 * @todo - nedovoluje null, ale je optional
	 */
	private ?string $name;

	/**
	 * @var list<string>
	 *
	 * @ListOf(@StringValue())
	 */
	private array $scopes;

	/**
	 * @param list<string> $scopes
	 */
	private function __construct(?string $name, array $scopes)
	{
		// TODO - nevalidní kombinace (viz statické konstruktory)
		$this->name = $name;
		$this->scopes = $scopes;
	}

	public static function createOptional(): self
	{
		static $inst = null;

		if ($inst === null) {
			$inst = new self(null, []);
		}

		return $inst;
	}

	/**
	 * @param list<string> $scopes
	 */
	public static function create(string $name, array $scopes = []): self
	{
		return new self($name, $scopes);
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return list<string>
	 */
	public function getScopes(): array
	{
		return $this->scopes;
	}

	public function toArray(): array
	{
		if ($this->name === null) {
			return [new stdClass()];
		}

		return [$this->name => $this->scopes];
	}

}
