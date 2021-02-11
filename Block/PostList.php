<?php
namespace Slavko98\Dev98\Block;

class PostList extends \Magento\Framework\View\Element\Template
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
     * Return all posts from the database.
     */
    public function getPosts()
    {
        $post = $this->postFactory->create();
        $posts = $post->getCollection()->toArray();
        return $posts;
    }
}