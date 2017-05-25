<?php
/**
 * Author: Joris Rietveld <jorisrietveld@gmail.com>
 * Created: 25-05-2017 10:43
 * Licence: GNU General Public licence version 3 <https://www.gnu.org/licenses/quick-guide-gplv3.html>
 */
declare(strict_types=1);

namespace WaterUpApi\Exception;


class InvalidTypeCastException extends \Exception
{
    /**
     * InvalidTypeCastException constructor that initiates the default classes properties.
     *
     * @param string     $message
     * @param int        $code
     * @param \Throwable $previous
     *
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}