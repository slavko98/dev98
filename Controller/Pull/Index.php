<?php
namespace Slavko98\Dev98\Controller\Pull;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $pullService;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Slavko98\Dev98\Model\PullService $pullService
        ) {
            parent::__construct($context);
            $this->pullService = $pullService;
    }

    /**
     * Call the pullService to pull posts from the blog and then redirect
     * back to the post list page.
     */
    public function execute()
    {
        $this->pullService->execute();
        // todo: still need to invalidate the full page cache here
        $this->_redirect('dev98blogposts');
    }
}