<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Attributes\Modifiers\FieldName;
use Orisai\ObjectMapper\MappedObject;

/**
 * @CreateWithoutConstructor()
 */
final class Reference extends MappedObject implements SpecObject
{

	/**
	 * @FieldName("$ref")
	 * @StringValue()
	 */
	private string $ref;

	/** @StringValue() */
	public ?string $summary = null;

	/** @StringValue() */
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

	protected static function of(string $spec, string $name): self
	{
		return new self("#/components/$spec/$name");
	}

	public function getRef(): string
	{
		return $this->ref;
	}

	public function toArray(): array
	{
		$data = [
			'$ref' => $this->ref,
		];

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		return $data;
	}

}
