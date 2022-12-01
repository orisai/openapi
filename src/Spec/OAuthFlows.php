<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;

/**
 * @CreateWithoutConstructor()
 */
final class OAuthFlows extends MappedObject implements SpecObject
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

	public function toArray(): array
	{
		$data = [];

		if ($this->implicit !== null) {
			$data['implicit'] = $this->implicit->toArray();
		}

		if ($this->password !== null) {
			$data['password'] = $this->password->toArray();
		}

		if ($this->clientCredentials !== null) {
			$data['clientCredentials'] = $this->clientCredentials->toArray();
		}

		if ($this->authorizationCode !== null) {
			$data['authorizationCode'] = $this->authorizationCode->toArray();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
