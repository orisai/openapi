<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Utils;

use Closure;
use function assert;
use function preg_match;
use function strpos;
use function strtolower;
use function substr;
use function uksort;

/**
 * @internal
 */
final class MediaTypes
{

	private const Expression = /** @lang PhpRegExp */
		<<<'REGEX'
@^
	(
		(\*/\*)
		| (
			(application|audio|font|example|image|message|model|multipart|text|video|x-(?:[0-9A-Za-z!#$%&'*+.^_`|~-]+))
			/
			([0-9A-Za-z!#$%&'*+.^_`|~-]+)
		)
	)
	(
		(?:[ \t]*;[ \t]*[0-9A-Za-z!#$%&'*+.^_`|~-]+=(?:[0-9A-Za-z!#$%&'*+.^_`|~-]+|\"(?:[^\"\\\\]|\\.)*\"))*
	)
$@ix
REGEX;

	private function __construct()
	{
		// Static class
	}

	/**
	 * @param non-empty-string $mediaType
	 * @return non-empty-string
	 */
	public static function format(string $mediaType): string
	{
		return strtolower($mediaType);
	}

	public static function isValid(string $mediaType): bool
	{
		return preg_match(self::Expression, $mediaType) === 1;
	}

	/**
	 * @template T of array<string, mixed>
	 * @param T $mediaTypes
	 * @param-out T $mediaTypes
	 */
	public static function sortTypesInKeys(array &$mediaTypes): void
	{
		uksort($mediaTypes, self::getSortFunction());
	}

	/**
	 * @return Closure(string, string): int
	 * @phpstan-return Closure(string, string): (-1|0|1)
	 */
	private static function getSortFunction(): Closure
	{
		return static function (string $a, string $b): int {
			$aIsWildcard = $a === '*/*';
			$bIsWildcard = $b === '*/*';

			if ($aIsWildcard && !$bIsWildcard) {
				/** @infection-ignore-all */
				return 1;
			}

			if (!$aIsWildcard && $bIsWildcard) {
				/** @infection-ignore-all */
				return -1;
			}

			$aPosition = strpos($a, '/');
			assert($aPosition !== false);
			$aLeft = substr($a, 0, $aPosition);

			$bPosition = strpos($b, '/');
			assert($bPosition !== false);
			$bLeft = substr($b, 0, $bPosition);

			$leftCompare = $aLeft <=> $bLeft;
			if ($leftCompare !== 0) {
				return $leftCompare;
			}

			$aHasExtension = strpos($a, '/x-') !== false;
			$bHasExtension = strpos($b, '/x-') !== false;

			if ($aHasExtension && !$bHasExtension) {
				/** @infection-ignore-all */
				return 1;
			}

			if (!$aHasExtension && $bHasExtension) {
				/** @infection-ignore-all */
				return -1;
			}

			$aIsSubtypeWildcard = strpos($a, '/*') !== false;
			$bIsSubtypeWildcard = strpos($b, '/*') !== false;

			if ($aIsSubtypeWildcard && !$bIsSubtypeWildcard) {
				/** @infection-ignore-all */
				return 1;
			}

			if (!$aIsSubtypeWildcard && $bIsSubtypeWildcard) {
				/** @infection-ignore-all */
				return -1;
			}

			/** @infection-ignore-all */
			return $a > $b ? 1 : -1;
		};
	}

}
