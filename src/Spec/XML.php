<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use DOMElement;
use DOMException;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\BoolValue;
use Orisai\ObjectMapper\Rules\StringValue;

final class XML implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	private ?string $name = null;

	/** @StringValue() */
	public ?string $namespace = null;

	/** @StringValue() */
	private ?string $prefix = null;

	/** @BoolValue() */
	public bool $attribute = false;

	/** @BoolValue() */
	public bool $wrapped = false;

	public function setName(?string $name): void
	{
		if ($name !== null) {
			$this->checkXmlTagName($name, 'name');
		}

		$this->name = $name;
	}

	public function setPrefix(?string $prefix): void
	{
		if ($prefix !== null) {
			$this->checkXmlTagName($prefix, 'prefix');
		}

		$this->prefix = $prefix;
	}

	private function checkXmlTagName(string $name, string $type): void
	{
		try {
			new DOMElement($name);
		} catch (DOMException $e) {
			$message = Message::create()
				->withContext("Setting XML $type with value '$name'.")
				->withProblem('Value is not valid in context of xml tag name.');

			throw InvalidArgument::create()
				->withMessage($message);
		}
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [];

		if ($this->name !== null) {
			$data['name'] = $this->name;
		}

		if ($this->namespace !== null) {
			$data['namespace'] = $this->namespace;
		}

		if ($this->prefix !== null) {
			$data['prefix'] = $this->prefix;
		}

		if ($this->attribute) {
			$data['attribute'] = $this->attribute;
		}

		if ($this->wrapped) {
			$data['wrapped'] = $this->wrapped;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
