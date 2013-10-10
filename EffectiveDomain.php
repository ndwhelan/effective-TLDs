<?
/**
 * Copyright 2013 Indeed, Inc
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once('effectiveTLDs.inc.php');

class EffectiveDomain {
	/**
	 * Ge the the public suffix
	 * @param $domain
	 * @return string
	 */
	public static function getPublicSuffix($domain) {
		$parts = explode('.', $domain);

		return self::getPubSuffHelper(null, $parts, TldSuffix::$tldSuffixes);
	}

	private static function getPubSuffHelper($nonPublicParts, $partsLeft, &$tlds) {
		$part = array_pop($partsLeft);

		if (empty($part)) {
			return null;
		}

		if (!array_key_exists($part, $tlds)) {
			if (array_key_exists('*', $tlds)) {
				return self::getPubSuffHelper($part.(!empty($nonPublicParts) ? '.'.$nonPublicParts : ''), $partsLeft, $tlds['*']);
			}
			if (array_key_exists('#', $tlds) && $tlds['#'] === true) {
				return $part.(!empty($nonPublicParts) ? '.'.$nonPublicParts : '');
			} else {
				return null;
			}
		} elseif ($tlds[$part] === '!') {
			return $part.(!empty($nonPublicParts) ? '.'.$nonPublicParts : '');
		} elseif (array_key_exists('*', $tlds)) {
			// Special case with a wildcard and a domain.
			// Such as domain.blogspot.com.ar should be domain.blogspot.com.ar,
			// whereas domain.com.ar should be domain.com.ar, due to a blogspot
			// domains case in the latest suffix list.
			$potential = self::getPubSuffHelper($part.(!empty($nonPublicParts) ? '.'.$nonPublicParts : ''), $partsLeft, $tlds[$part]);
			if (empty($potential)) {
				return self::getPubSuffHelper($part.(!empty($nonPublicParts) ? '.'.$nonPublicParts : ''), $partsLeft, $tlds['*']);
			}
			return $potential;
		} else {
			return self::getPubSuffHelper($part.(!empty($nonPublicParts) ? '.'.$nonPublicParts : ''), $partsLeft, $tlds[$part]);
		}
	}
}
