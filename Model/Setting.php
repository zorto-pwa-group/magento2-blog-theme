<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\BlogTheme\Model;

use Magento\Framework\Model\AbstractModel;
use ZT\BlogTheme\Api\Data\SettingInterface;

/**
 * @method getTitle()
 */
class Setting extends AbstractModel implements SettingInterface
{
    /**
     * Prefix of model events names
     * ztpwa_blog_theme_settings_save_before
     * ztpwa_blog_theme_settings_save_after
     * @var string
     */
    protected $_eventPrefix = 'ztpwa_blog_theme_settings';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'theme_setting';

    /**
     * Get ID
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->_getData(self::TEXT_ID);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ZT\BlogTheme\Model\ResourceModel\Setting');
    }
}
