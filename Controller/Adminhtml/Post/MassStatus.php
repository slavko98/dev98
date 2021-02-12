<?php
namespace Slavko98\Dev98\Controller\Adminhtml\Post;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Slavko98\Dev98\Model\PostFactory;
use Slavko98\Dev98\Model\ResourceModel\Post\CollectionFactory;

class MassStatus extends \Magento\Catalog\Controller\Adminhtml\Product implements HttpPostActionInterface
{
    protected $filter;
    protected $collectionFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Product\Builder $productBuilder,
        Filter $filter,
        PostFactory $postFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->postFactory = $postFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $productBuilder);
    }

    /**
     * Update post(s) status action
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $postIds = $collection->getAllIds();
        $status = (int) $this->getRequest()->getParam('status');

        try {
            $this->updateStatus($postIds, $status);
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been updated.', count($postIds))
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while updating the post(s) status.')
            );
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('slavko98_dev98/grid/index');
    }

    protected function updateStatus($postIds, $status)
    {
        $condition = "uid in (" . implode(',', $postIds) . ")";

        $collection = $this->collectionFactory->create();

        $collection->getConnection()->update(
            $collection->getResource()->getTable('slavko98_dev98_post'),
            ['status' => $status],
            $condition
        );
    }
}