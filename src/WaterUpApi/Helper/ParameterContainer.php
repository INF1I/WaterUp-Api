<?php
/**
 * Author: Joris Rietveld <jorisrietveld@gmail.com>
 * Created: 25-05-2017 02:34
 * Licence: GNU General Public licence version 3 <https://www.gnu.org/licenses/quick-guide-gplv3.html>
 */
declare(strict_types=1);

namespace StendenINF1I\WaterUpApi\Helper;


class ParameterContainer extends BaseContainer
{
    protected static $containerType = __CLASS__;

    /**
     * ParameterBaseContainer constructor that initiates the default classes properties.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        self::setContainerType(__CLASS__);
    }


}