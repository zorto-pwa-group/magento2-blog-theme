<?php
namespace ZT\BlogTheme\Controller\Adminhtml\Setting;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use ZT\BlogTheme\Api\Data\SettingInterface;

/**
 * Edit PWA Blog Theme action.
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PWA_BlogTheme::configuration';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Init actions
     * load layout, set active menu and breadcrumbs
     * @return Page
     */
    protected function _initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('PWA_BlogTheme::configuration')
            ->addBreadcrumb(__('PWA'), __('Blog'))
            ->addBreadcrumb(__('Theme'), __('Settings'));
        return $resultPage;
    }

    /**
     * Edit rozy theme setting
     *
     * @return Page|Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id =  $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(\ZT\BlogTheme\Model\Setting::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id, 'identifier');
        }

        $this->_coreRegistry->register('rozy_theme_setting', $model);

        // 5. Build edit form
        /** @var Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Theme Setting') : __('New Theme Setting'),
            $id ? __('Edit Theme Setting') : __('New Theme Setting')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('PWA Blog Theme Settings'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Theme Setting'));

        return $resultPage;
    }
}
