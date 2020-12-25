<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\BlogTheme\Model;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use ZT\BlogTheme\Api\Data\SettingInterface;
use ZT\BlogTheme\Api\SettingRepositoryInterface;
use ZT\BlogTheme\Model\ResourceModel\Setting as ResourceSetting;
use ZT\BlogTheme\Model\ResourceModel\Setting\CollectionFactory;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

class SettingRepository implements SettingRepositoryInterface
{
    /**
     * @var ResourceSetting
     */
    protected $_resourceSetting;
    /**
     * @var SettingFactory
     */
    protected $_tagFactory;
    /**
     * @var CollectionFactory
     */
    protected $settingCollectionFactory;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * SettingRepository constructor.
     * @param ResourceSetting $resource
     * @param SettingFactory $settingFactory
     * @param CollectionFactory $settingCollectionFactory
     * @param SearchResultsFactory $searchResultsFactory
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        ResourceSetting $resource,
        SettingFactory $settingFactory,
        CollectionFactory $settingCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->_resourceSetting = $resource;
        $this->_tagFactory = $settingFactory;
        $this->tagCollectionFactory = $settingCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Save Setting data
     *
     * @param SettingInterface $setting
     * @return Setting
     * @throws CouldNotSaveException
     */
    public function save(SettingInterface $setting)
    {
        try {
            $this->_resourceSetting->save($setting);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $setting;
    }

    /**
     * Delete Setting by given Setting Identity
     *
     * @param string $settingId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($settingId)
    {
        return $this->delete($this->getById($settingId));
    }

    /**
     * Delete Setting
     *
     * @param SettingInterface $setting
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(SettingInterface $setting)
    {
        try {
            $this->_resourceSetting->delete($setting);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Load Setting data by given Setting Identity
     *
     * @param string $settingId
     * @return Setting
     * @throws NoSuchEntityException
     */
    public function getById($settingId)
    {
        $setting = $this->_tagFactory->create();
        $this->_resourceSetting->load($setting, $settingId);
        if (!$setting->getId()) {
            throw new NoSuchEntityException(__('Setting with id "%1" does not exist.', $settingId));
        }
        return $setting;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->tagCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var searchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setItems($collection->getData());

        return $searchResult;
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Magento\Cms\Model\Api\SearchCriteria\PageCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
