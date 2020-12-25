<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\BlogTheme\Api;

use Magento\Framework\Exception\LocalizedException;
use ZT\BlogTheme\Api\Data\SettingInterface;

interface SettingRepositoryInterface
{
    /**
     * Save setting.
     *
     * @param SettingInterface $setting
     * @return SettingInterface
     * @throws LocalizedException
     */
    public function save(SettingInterface $setting);

    /**
     * Retrieve setting.
     *
     * @param int $settingId
     * @return SettingInterface
     * @throws LocalizedException
     */
    public function getById($settingId);
}
