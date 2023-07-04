<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\StringValue;

/**
 * @CreateWithoutConstructor()
 */
final class Info implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public string $title;

	/** @StringValue() */
	public ?string $summary = null;

	/** @StringValue() */
	public ?string $description = null;

	/** @StringValue() */
	public ?string $termsOfService = null;

	/** @MappedObjectValue(Contact::class) */
	public Contact $contact;

	/** @MappedObjectValue(License::class) */
	public ?License $license = null;

	/** @StringValue() */
	public string $version;

	public function __construct(string $title, string $version)
	{
		$this->title = $title;
		$this->version = $version;
		$this->contact = new Contact();
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [
			'title' => $this->title,
			'version' => $this->version,
		];

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->termsOfService !== null) {
			$data['termsOfService'] = $this->termsOfService;
		}

		$contactData = $this->contact->toRaw();
		if ($contactData !== []) {
			$data['contact'] = $contactData;
		}

		if ($this->license !== null) {
			$data['license'] = $this->license->toRaw();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
