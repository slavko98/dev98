<?php
namespace Slavko98\Dev98\Block;

class Post extends \Magento\Framework\View\Element\Template
{
    protected $postFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Slavko98\Dev98\Model\PostFactory $postFactory,
        array $data = []
    ){
        $this->postFactory = $postFactory;
        parent::__construct($context, $data);
    }

    /**
     * This function will return a single post. The post id is to be passed
     * as a parameter from the URL.
     */
    public function getPost()
    {
        $post = array();
        $requestParams = $this->getRequest()->getParams();
        if (array_key_exists('id', $requestParams)) {
            $post = $this->postFactory->create()->load($requestParams['id'])->getData();
        }

        return $post;
    }
}