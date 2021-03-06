<?php
/**
 * File RouteEncoder.php
 *
 * PHP version 5.4+
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   3.1.0
 * @link      http://www.sweelix.net
 * @category  components
 * @package   sweelix.yii1.ext.components
 */

namespace sweelix\yii1\ext\components;

/**
 * This class allow cms data serialization
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   3.1.0
 * @link      http://www.sweelix.net
 * @category  components
 * @package   sweelix.yii1.ext.components
 * @since     3.1.0
 */
class RouteEncoder {
	private static $routes = [];
	/**
	 * Encode CMS parameters into a string. This function
	 * is mainly used by the url manager to encode parameters
	 * into the route
	 *
	 * @param integer $contentId content id (null if not known)
	 * @param integer $nodeId    node id (null if not known)
	 * @param integer $tagId     tag id (null if not known)
	 * @param integer $groupId   group id (null if not known)
	 *
	 * @return string
	 * @since  3.1.0
	 */
	public static function encode($contentId=null, $nodeId=null, $tagId=null, $groupId=null) {
		$header = 0;
		$result = "";
		if($contentId !== null) {
			$hex = base_convert($contentId, 10, 32);
			$result .= base_convert(strlen($hex), 10, 32);
			$result .= $hex;
			$header = $header | 8;
		}
		if($nodeId !== null) {
			$hex = base_convert($nodeId, 10, 32);
			$result .= base_convert(strlen($hex), 10, 32);
			$result .= $hex;
			$header = $header | 4;
		}
		if($tagId !== null) {
			$hex = base_convert($tagId, 10, 32);
			$result .= base_convert(strlen($hex), 10, 32);
			$result .= $hex;
			$header = $header | 2;
		}
		if($groupId !== null) {
			$hex = base_convert($groupId, 10, 32);
			$result .= base_convert(strlen($hex), 10, 32);
			$result .= $hex;
			$header = $header | 1;
		}
		$result = base_convert($header, 10, 32).$result;
		$crc = base_convert(crc32($result), 10, 32);
		return $crc.'z'.$result;
	}

	/**
	 * Decode route into CMS parameters. This function
	 * is mainly used by the url manager to decode the route
	 * into parameters
	 *
	 * @param string  $route   the route to decode
	 *
	 * @return array
	 * @since  3.1.0
	 */
	public static function decode($route='') {
		if(isset(self::$routes[$route]) === false) {
			$result = false;
			$contentId = null;
			$nodeId = null;
			$tagId = null;
			$groupId = null;
			$pos = strpos($route,'z');
			$crc = substr($route, 0, $pos);
			$route = substr($route, $pos+1);
			$crcComputed = base_convert(crc32($route), 10, 32);
			if($crcComputed === $crc) {
				$header = base_convert($route[0], 32, 10);
				$offset = 1;
				if(($header & 0x8) === 0x8) {
					$contentId = self::subDecode($route, $offset);
				}
				if(($header & 0x4) === 0x4) {
					$nodeId = self::subDecode($route, $offset);
				}
				if(($header & 0x2) === 0x2) {
					$tagId = self::subDecode($route, $offset);
				}
				if(($header & 0x1) === 0x1) {
					$groupId = self::subDecode($route, $offset);
				}
				$result = [$contentId, $nodeId, $tagId, $groupId];
			} else {
				$result = false;
			}
			self::$routes[$route] = $result;
		}
		return self::$routes[$route];
	}
	/**
	 * Decode simple pattern and extract the final value
	 *
	 * @param string  $data    the full route data
	 * @param integer &$offset where to start decoding process
	 *
	 * @return integer
	 * @since  1.0.0
	 */
	private static function subDecode($data, &$offset) {
		$headerLen = base_convert($data[$offset], 32, 10);
		$str = substr($data, ++$offset, $headerLen);
		$offset = $offset + $headerLen;
		return base_convert($str, 32, 10);
	}
}

