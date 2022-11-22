<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use DOMElement;
use DOMException;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;

final class XML implements SpecObject
{

	use SpecObjectSupportsExtensions;

	private ?string $name = null;

	public ?string $namespace = null;

	private ?string $prefix = null;

	public bool $attribute = false;

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

	public function toArray(): array
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
