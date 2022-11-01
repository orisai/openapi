<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use stdClass;

final class SecurityRequirement implements SpecObject
{

	/** @readonly */
	private ?string $name;

	/** @var list<string> */
	private array $scopes;

	/**
	 * @param list<string> $scopes
	 */
	private function __construct(?string $name, array $scopes)
	{
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
		//TODO - v nadřazené komponentě unikátní název (stejně jako Tag)
		//TODO - název musí odpovídat SecurityScheme v rámci Components
		//TODO - pokud obsahuje více scopes, tak musí odpovídat všechny
		//TODO - pouze jeden SecurityRequirement list (na OpenAPI nebo Operation) musí odpovídat aby uživatel byl autorizován
		if ($this->name === null) {
			return [new stdClass()];
		}

		return [$this->name => $this->scopes];
	}

}
