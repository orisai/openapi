<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;

final class Parameter implements SpecObject
{

	use SupportsSpecExtensions;

	public string $name;

	public ParameterIn $in;

	public ?string $description = null;

	public bool $required = false;

	public bool $deprecated = false;

	public bool $allowEmptyValue = false;

	public ?string $style = null;

	public bool $explode = false;

	public bool $allowReserved = false;

	public Schema $schema;

	/** @var mixed */
	public $example;

	/** @var array<string, Example|Reference> */
	public array $examples = [];

	/** @var array<string, MediaType> */
	public array $content = [];

	public function __construct(string $name, ParameterIn $in)
	{
		$this->name = $name;
		$this->in = $in;
		$this->schema = new Schema();
		unset($this->example);

		if ($in === ParameterIn::path()) {
			$this->required = true;
		}
	}

	public function toArray(): array
	{
		//TODO - pro type header a name "Accept", "Content-Type" a "Authorization" má být definice parametru ignorovaná??
		//			- co to znamená?
		//TODO - ve specifikaci je, že jsou jména case sensitive, ale hlavičky jsou case unsensitive
		$data = [
			'name' => $this->name,
			'in' => $this->in->value,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		//TODO - required nesmí být false, pokud jde o path
		if ($this->required) {
			$data['required'] = $this->required;
		}

		if ($this->deprecated) {
			$data['deprecated'] = $this->deprecated;
		}

		//TODO - pouze pro query
		//TODO - If style is used, and if behavior is n/a (cannot be serialized),
		//			the value of allowEmptyValue SHALL be ignored ??
		if ($this->allowEmptyValue) {
			$data['allowEmptyValue'] = $this->allowEmptyValue;
		}

		//TODO - Default values (based on value of in): for query - form; for path - simple; for header - simple; for cookie - form.
		//TODO - předdefinované styly (závislé na in)
		if ($this->style !== null) {
			$data['style'] = $this->style;
		}

		//TODO - pro style=form je default true (ale může se změnit??)
		//TODO - něco s array a object, pro jiné typy parametrů žádný efekt
		if ($this->explode) {
			$data['explode'] = $this->explode;
		}

		//TODO - nějaká validace hodnot, když je false
		//TODO - pouze pro in=query
		if ($this->allowReserved) {
			$data['allowReserved'] = $this->allowReserved;
		}

		$schemaData = $this->schema->toArray();
		if ($schemaData !== []) {
			$data['schema'] = $schemaData;
		}

		//TODO - musí matchnout schema a encoding, pokud jsou nastavené
		//		- jak se nastavuje encoding?? (asi v media type)
		//TODO - pokud existuje examples, tak nesmí existovat example a naopak
		$valueRef = new ReflectionProperty($this, 'example');
		if ($valueRef->isInitialized($this)) {
			$data['example'] = $this->example;
		}

		//TODO - musí matchnout encoding, pokud je nastavený (schema ne? jiný popisek než example)
		//		- jak se nastavuje encoding?? (asi v media type)
		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		//TODO - parametr musí obsahovat schema nebo content, ale ne obojí
		//TODO - example a examples example musí následovat serializační strategii parametru
		if ($this->content !== []) {
			$data['content'] = SpecUtils::specsToArray($this->content);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
