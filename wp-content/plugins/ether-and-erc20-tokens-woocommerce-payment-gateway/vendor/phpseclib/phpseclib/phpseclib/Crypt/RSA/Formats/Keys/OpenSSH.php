<?php

/**
 * OpenSSH Formatted RSA Key Handler
 *
 * PHP version 5
 *
 * Place in $HOME/.ssh/authorized_keys
 *
 * @category  Crypt
 * @package   RSA
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2015 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Ethereumico\Epg\Dependencies\phpseclib3\Crypt\RSA\Formats\Keys;

use Ethereumico\Epg\Dependencies\ParagonIE\ConstantTime\Base64;
use Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger;
use Ethereumico\Epg\Dependencies\phpseclib3\Common\Functions\Strings;
use Ethereumico\Epg\Dependencies\phpseclib3\Crypt\Common\Formats\Keys\OpenSSH as Progenitor;
/**
 * OpenSSH Formatted RSA Key Handler
 *
 * @package RSA
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
abstract class OpenSSH extends \Ethereumico\Epg\Dependencies\phpseclib3\Crypt\Common\Formats\Keys\OpenSSH
{
    /**
     * Supported Key Types
     *
     * @var array
     */
    protected static $types = ['ssh-rsa'];
    /**
     * Break a public or private key down into its constituent components
     *
     * @access public
     * @param string $key
     * @param string $password optional
     * @return array
     */
    public static function load($key, $password = '')
    {
        static $one;
        if (!isset($one)) {
            $one = new \Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger(1);
        }
        $parsed = parent::load($key, $password);
        if (isset($parsed['paddedKey'])) {
            list($type) = \Ethereumico\Epg\Dependencies\phpseclib3\Common\Functions\Strings::unpackSSH2('s', $parsed['paddedKey']);
            if ($type != $parsed['type']) {
                throw new \RuntimeException("The public and private keys are not of the same type ({$type} vs {$parsed['type']})");
            }
            $primes = $coefficients = [];
            list($modulus, $publicExponent, $privateExponent, $coefficients[2], $primes[1], $primes[2], $comment, ) = \Ethereumico\Epg\Dependencies\phpseclib3\Common\Functions\Strings::unpackSSH2('i6s', $parsed['paddedKey']);
            $temp = $primes[1]->subtract($one);
            $exponents = [1 => $publicExponent->modInverse($temp)];
            $temp = $primes[2]->subtract($one);
            $exponents[] = $publicExponent->modInverse($temp);
            $isPublicKey = \false;
            return \compact('publicExponent', 'modulus', 'privateExponent', 'primes', 'coefficients', 'exponents', 'comment', 'isPublicKey');
        }
        list($publicExponent, $modulus) = \Ethereumico\Epg\Dependencies\phpseclib3\Common\Functions\Strings::unpackSSH2('ii', $parsed['publicKey']);
        return ['isPublicKey' => \true, 'modulus' => $modulus, 'publicExponent' => $publicExponent, 'comment' => $parsed['comment']];
    }
    /**
     * Convert a public key to the appropriate format
     *
     * @access public
     * @param \phpseclib3\Math\BigInteger $n
     * @param \phpseclib3\Math\BigInteger $e
     * @param array $options optional
     * @return string
     */
    public static function savePublicKey(\Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger $n, \Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger $e, array $options = [])
    {
        $RSAPublicKey = \Ethereumico\Epg\Dependencies\phpseclib3\Common\Functions\Strings::packSSH2('sii', 'ssh-rsa', $e, $n);
        if (isset($options['binary']) ? $options['binary'] : self::$binary) {
            return $RSAPublicKey;
        }
        $comment = isset($options['comment']) ? $options['comment'] : self::$comment;
        $RSAPublicKey = 'ssh-rsa ' . \base64_encode($RSAPublicKey) . ' ' . $comment;
        return $RSAPublicKey;
    }
    /**
     * Convert a private key to the appropriate format.
     *
     * @access public
     * @param \phpseclib3\Math\BigInteger $n
     * @param \phpseclib3\Math\BigInteger $e
     * @param \phpseclib3\Math\BigInteger $d
     * @param array $primes
     * @param array $exponents
     * @param array $coefficients
     * @param string $password optional
     * @param array $options optional
     * @return string
     */
    public static function savePrivateKey(\Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger $n, \Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger $e, \Ethereumico\Epg\Dependencies\phpseclib3\Math\BigInteger $d, array $primes, array $exponents, array $coefficients, $password = '', array $options = [])
    {
        $publicKey = self::savePublicKey($n, $e, ['binary' => \true]);
        $privateKey = \Ethereumico\Epg\Dependencies\phpseclib3\Common\Functions\Strings::packSSH2('si6', 'ssh-rsa', $n, $e, $d, $coefficients[2], $primes[1], $primes[2]);
        return self::wrapPrivateKey($publicKey, $privateKey, $password, $options);
    }
}
