<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Reference implements SpecObject
{

	public string $ref;

	public ?string $summary = null;

	public ?string $description = null;

	public function __construct(string $ref)
	{
		$this->ref = $ref;
	}

	public static function ofCallback(string $name): self
	{
		return self::of('callbacks', $name);
	}

	public static function ofExample(string $name): self
	{
		return self::of('examples', $name);
	}

	public static function ofHeader(string $name): self
	{
		return self::of('headers', $name);
	}

	public static function ofLink(string $name): self
	{
		return self::of('links', $name);
	}

	public static function ofParameter(string $name): self
	{
		return self::of('parameters', $name);
	}

	public static function ofResponse(string $name): self
	{
		return self::of('responses', $name);
	}

	public static function ofRequestBody(string $name): self
	{
		return self::of('requestBodies', $name);
	}

	public static function ofSecurityScheme(string $name): self
	{
		return self::of('securitySchemes', $name);
	}

	public static function ofSchema(string $name): self
	{
		return self::of('schemas', $name);
	}

	private static function of(string $spec, string $name): self
	{
		return new self("#/components/$spec/$name");
	}

	public function toArray(): array
	{
		$data = [
			'$ref' => $this->ref,
		];

		//TODO - přepisuje popis odkazovaného objektu a pokud objekt popis nedovoluje, tak nemá žádný efekt
		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		//TODO - přepisuje popis odkazovaného objektu a pokud objekt popis nedovoluje, tak nemá žádný efekt
		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		return $data;
	}

}
