<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\BlogTheme\Controller\Adminhtml\Setting;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use ZT\BlogTheme\Api\SettingRepositoryInterface;
use ZT\BlogTheme\Model\SettingFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Exception;
use Magento\Framework\Event\ManagerInterface as EventManager;

// no use
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use ZT\BlogTheme\Model\Setting;

/**
 * BlogTheme setting save controller
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PWA_BlogTheme::save_settings';

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $_dataPersistor;

    /**
     * @var SettingFactory
     */
    protected $_settingFactory;

    /**
     * @var SettingRepositoryInterface
     */
    protected $_settingRepository;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var TimezoneInterface
     */
    protected $_timezone;


    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        DataPersistorInterface $dataPersistor,
        SettingFactory $settingFactory = null,
        SettingRepositoryInterface $settingRepository = null,
        EventManager $eventManager,
        TimezoneInterface $timezone
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_dataPersistor = $dataPersistor;
        $this->_settingFactory = $settingFactory
            ?: ObjectManager::getInstance()->get(SettingFactory::class);
        $this->_settingRepository = $settingRepository
            ?: ObjectManager::getInstance()
                ->get(SettingRepositoryInterface::class);
        $this->eventManager = $eventManager;
        $this->_timezone = $timezone;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('identifier');
            if ($id) {
                try {
                    $model = $this->_settingRepository->getById($id);
                } catch (LocalizedException $e) {
                    /** @var Setting $model */
                    $model = $this->_settingFactory->create();
                }
            }
            $hasError = false;
            $model->setData($data);
            try {
                $this->_beforeSave($model);
                $this->_settingRepository->save($model);
            } catch (Exception $e) {
                $hasError = true;
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the blog setting.'));
            }
            if ($hasError) {
                $this->_dataPersistor->set('blog_rozy_theme_setting_form_data', $data);
            }
            $this->messageManager->addSuccessMessage(__('You saved the setting.'));
        }
        $id = !empty($id) ? $id : $model->getSettingId();
        return $resultRedirect->setPath('pwablogrozy/*/edit', ['id' => $id]);
    }

    /**
     * Before model save
     * Set blog logo
     * Set blog collapse logo
     * Set creation time
     * Set update time
     * Set blog main banner
     * @param  Setting $model
     * @return void
     */
    protected function _beforeSave($model)
    {
        $data = $model->getData();

        $todayDate = $this->_timezone->date()->format('Y-m-d H:i:s');

        /* Prepare update time */
        if ($model->getCreationTime()) {
            $model->setUpdateTime($todayDate);
        }

        /* Prepare creation time */
        if (!$model->getCreationTime()) {
            $model->setCreationTime($todayDate);
        }

        $this->eventManager->dispatch('controller_pwa_theme_setting_save_before', ['data' => $data, 'model' => $model, 'objectManager' => $this->_objectManager]);
    }
}
