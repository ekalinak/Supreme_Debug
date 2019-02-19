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

namespace Supreme\Debug\Plugin\Block;

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
    public function aroundToHtml($subject, callable $proceed)
    {
        $enabled = (boolean)$this->_scopeConfig->getValue(Config::PATH_BLOCK_INFO_ENABLE);
        if (!$enabled) {
            return $proceed();
        }
        $block_name = 'B: ' . $this->getName($subject);
        $block_name .= $subject->getParentBlock() ? ' | ' . $this->getName($subject->getParentBlock()) : '';
        $block_template = ($subject->getTemplate() ? ' | ' . $subject->getTemplate() : '');
        $returnValue = "\n<!-- " . $block_name . $block_template . " -->";
        $returnValue .= $proceed();
        $returnValue .= "\n<!-- " . $block_name . " | END -->";
        return $returnValue;
    }

    /**
     * @param $subject
     * @return mixed
     */
    private function getName($subject)
    {
        return $subject->getNameInLayout() ?
            $subject->getNameInLayout() :
            str_replace(array('\Interceptor', '\\'), array('', '/'), get_class($subject));
    }
}