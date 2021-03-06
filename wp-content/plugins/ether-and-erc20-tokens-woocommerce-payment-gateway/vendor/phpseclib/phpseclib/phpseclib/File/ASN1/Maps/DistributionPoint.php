<?php

/**
 * DistributionPoint
 *
 * PHP version 5
 *
 * @category  File
 * @package   ASN1
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1\Maps;

use Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1;
/**
 * DistributionPoint
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
abstract class DistributionPoint
{
    const MAP = ['type' => \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['distributionPoint' => ['constant' => 0, 'optional' => \true, 'explicit' => \true] + \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1\Maps\DistributionPointName::MAP, 'reasons' => ['constant' => 1, 'optional' => \true, 'implicit' => \true] + \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1\Maps\ReasonFlags::MAP, 'cRLIssuer' => ['constant' => 2, 'optional' => \true, 'implicit' => \true] + \Ethereumico\Epg\Dependencies\phpseclib3\File\ASN1\Maps\GeneralNames::MAP]];
}
