<?php

/**
 * ASN1 Signature Handler
 *
 * PHP version 5
 *
 * Handles signatures in the format described in
 * https://tools.ietf.org/html/rfc3279#section-2.2.3
 *
 * @category  Crypt
 * @package   Common
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Ethereumico\Epg\Dependencies\phpseclib3\Crypt\EC\Formats\Signature;

use Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger;
use Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1 as Encoder;
use Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1\Maps\EcdsaSigValue;
/**
 * ASN1 Signature Handler
 *
 * @package Common
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
abstract class ASN1
{
    /**
     * Loads a signature
     *
     * @access public
     * @param string $sig
     * @return array
     */
    public static function load($sig)
    {
        if (!\is_string($sig)) {
            return \false;
        }
        $decoded = \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1::decodeBER($sig);
        if (empty($decoded)) {
            return \false;
        }
        $components = \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1::asn1map($decoded[0], \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1\Maps\EcdsaSigValue::MAP);
        return $components;
    }
    /**
     * Returns a signature in the appropriate format
     *
     * @access public
     * @param \phpseclib3\Math\BigInteger $r
     * @param \phpseclib3\Math\BigInteger $s
     * @return string
     */
    public static function save(\Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger $r, \Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger $s)
    {
        return \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1::encodeDER(\compact('r', 's'), \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1\Maps\EcdsaSigValue::MAP);
    }
}
