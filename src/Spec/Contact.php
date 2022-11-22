<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Contact implements SpecObject
{

	use SpecObjectSupportsExtensions;

	public ?string $name = null;

	public ?string $url = null;

	public ?string $email = null;

	public function toArray(): array
	{
		$data = [];

		if ($this->name !== null) {
			$data['name'] = $this->name;
		}

		if ($this->url !== null) {
			$data['url'] = $this->url;
		}

		if ($this->email !== null) {
			$data['email'] = $this->email;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
