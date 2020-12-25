<?php
/**
 * Copyright ZT. All rights reserved..
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace ZT\BlogTheme\Api\Data;

/**
 * Blog Setting interface.
 * @api
 * @since 100.0.2
 */
interface SettingInterface
{
    const TEXT_ID = 'identifier';

    const ID = 'setting_id';

    const ROZY_THEME_KEY_ID = 'rozy';

    /**
     * Because this module does not use REST or SOAP API,
     * so setter & getter functions in API Data Interface is not needed.
     */
}
