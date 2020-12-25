<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\BlogTheme\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use ZT\BlogTheme\Api\Data\SettingInterface;

/**
 * Blog setting resource model
 */
class Setting extends AbstractDb
{
    /**
     * Initialize resource model
     * Get table name from config
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ztpwa_blog_theme_settings', SettingInterface::TEXT_ID);
    }
}
