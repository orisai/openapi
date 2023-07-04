<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\MappedObjectValue;

final class OAuthFlows implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/** @MappedObjectValue(ImplicitOAuthFlow::class) */
	public ?ImplicitOAuthFlow $implicit = null;

	/** @MappedObjectValue(PasswordOAuthFlow::class) */
	public ?PasswordOAuthFlow $password = null;

	/** @MappedObjectValue(ClientCredentialsOAuthFlow::class) */
	public ?ClientCredentialsOAuthFlow $clientCredentials = null;

	/** @MappedObjectValue(AuthorizationCodeOAuthFlow::class) */
	public ?AuthorizationCodeOAuthFlow $authorizationCode = null;

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [];

		if ($this->implicit !== null) {
			$data['implicit'] = $this->implicit->toRaw();
		}

		if ($this->password !== null) {
			$data['password'] = $this->password->toRaw();
		}

		if ($this->clientCredentials !== null) {
			$data['clientCredentials'] = $this->clientCredentials->toRaw();
		}

		if ($this->authorizationCode !== null) {
			$data['authorizationCode'] = $this->authorizationCode->toRaw();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
