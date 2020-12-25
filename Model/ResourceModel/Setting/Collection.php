<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\BlogTheme\Model\ResourceModel\Setting;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use ZT\BlogTheme\Api\Data\SettingInterface;

/**
 * Blog theme setting collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = SettingInterface::TEXT_ID;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ZT\BlogTheme\Model\Setting', 'ZT\BlogTheme\Model\ResourceModel\Setting');
    }
}
