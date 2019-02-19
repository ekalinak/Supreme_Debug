<?php
/**
 * GymBeam s.r.o.
 *  
 * Copyright © GymBeam, All rights reserved.
 *  
 * @author K. Goč-Benka <kristian.goc-benka@gymbeam.com>
 * @copyright Copyright © 2019  GymBeam (https://gymbeam.com/)
 * @category GymBeam
 */

namespace Supreme\Debug\Plugin\Container;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Supreme\Debug\Helper\Config;

/**
 * Class Render
 * @package Cs2\Lfm\Plugin\Block
 */
class Render
{

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Render constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundRenderNonCachedElement($subject, callable $proceed, $name)
    {
        $enabled = (boolean)$this->_scopeConfig->getValue(Config::PATH_BLOCK_INFO_ENABLE);
        if (!$enabled) {
            return $proceed($name);
        }

        $returnValue = '';

        if (!$subject->isUiComponent($name) && !$subject->isBlock($name)) {
            $returnValue .= "<!-- C: " . $name . " -->\n";
        }

        $returnValue .= $proceed($name);

        if (!$subject->isUiComponent($name) && !$subject->isBlock($name)) {
            $returnValue .= "\n<!-- C: " . $name . " | END -->";
        }

        return $returnValue;
    }
}