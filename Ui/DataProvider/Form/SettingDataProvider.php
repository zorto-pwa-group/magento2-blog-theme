<?php
/**
 * Copyright © 2019 ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\BlogTheme\Ui\DataProvider\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use ZT\BlogTheme\Model\Setting;
use ZT\BlogTheme\Model\ResourceModel\Setting\Collection;
use ZT\BlogTheme\Model\ResourceModel\Setting\CollectionFactory;

/**
 * Class SettingDataProvider
 */
class SettingDataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $settingCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $settingCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $settingCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $items = $this->collection->getItems();
        /** @var $setting Setting */
        foreach ($items as $setting) {
            $setting = $setting->load($setting->getIdentifier(), 'identifier');
            $data = $setting->getData();

            /* Prepare logo, collapse logo & manin banner images */
            $map = [
                'logo_url' => 'getLogoUrl',
                'collapse_logo_url' => 'getCollapseLogoUrl',
                'main_banner' => 'getMainBanner'
            ];
            foreach ($map as $key => $method) {
                if (isset($data[$key])) {
                    $name = $data[$key];
                    unset($data[$key]);
                    $data[$key][0] = [
                        'name' => $name,
                        'url' => $mediaUrl . '/'.$setting->$method()
                    ];
                }
            }
            /* Set data */
            $this->loadedData[$setting->getId()] = $data;
        }

        $data = $this->dataPersistor->get('blog_rozy_theme_setting_form_data');
        if (!empty($data)) {
            $setting = $this->collection->getNewEmptyItem();
            $setting->setData($data);
            $this->loadedData[$setting->getId()] = $setting->getData();
            $this->dataPersistor->clear('blog_rozy_theme_setting_form_data');
        }
        return $this->loadedData;
    }
}
