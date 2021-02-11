<?php
namespace Slavko98\Dev98\Model;

class PullService
{
    // The IMPORT_SCHEMA defines how the values are mapped from
    // the source (xml) to the destination (the Post model).
    // The pattern is 'source field name' => 'destination field name',
    // or an array with custom conversion method.
    protected const IMPORT_SCHEMA = [
        'post-id' => 'post_id',
        'title' => 'title',
        'link' => 'link',
        'description' => 'post_description',
        'content:encoded' => 'post_content',
        'category' => [
            'methodName' => 'convertToJson',
            'dest_field' => 'tags'
        ],
        'dc:creator' => 'author',
        'pubDate' => 'created_at'
    ];

    protected $xmlParser;
    protected $serializer;
    protected $postFactory;

    public function __construct(
        \Magento\Framework\Xml\Parser $xmlParser,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        \Slavko98\Dev98\Model\PostFactory $postFactory
        ){
            $this->xmlParser = $xmlParser;
            $this->serializer = $serializer;
            $this->postFactory = $postFactory;
    }

    public function execute()
    {
        // Load the blog feed and process each blog post.
        $this->xmlParser->load('https://dev98.de/feed/');
        $feedArr = $this->xmlParser->xmlToArray();
        foreach ($feedArr['rss']['_value']['channel']['item'] as $blogPost) {
            $this->saveBlogPost($blogPost);
        }
    }

    // Process single blog post.
    protected function saveBlogPost($post)
    {
        // If post already exists in our database, skip.
        $newPost = $this->postFactory->create();
        $alreadyExists = $newPost->getCollection()
            ->addFieldToFilter('post_id', $post['post-id'])
            ->getSize();
        if ($alreadyExists) {
            // Post entry already exists.
            return;
        }

        // Loop over the IMPORT_SCHEMA and assign values to our $newPost model.
        foreach (self::IMPORT_SCHEMA as $src => $dest) {
            if (is_array($dest)) {
                // Complex assignment using custom conversion method.
                $methodName = $dest['methodName'];
                if (array_key_exists($src, $post) && method_exists($this, $methodName)) {
                    $value = $this->$methodName($post[$src]);
                } else {
                    $value = '';
                }
                $newPost->setData($dest['dest_field'], $value);
            } else {
                // Simple assignment.
                $newPost->setData($dest, array_key_exists($src, $post) ? $post[$src] : '');
            }
        }

        // Save post.
        $newPost->save();
    }

    // Custom conversion method returning a serialized JSON string.
    protected function convertToJson($categories)
    {
        return $this->serializer->serialize($categories);
    }

    /*
    // Custom conversion method returning a unix timestamp from a string formatted date.
    protected function convertStringToTimestamp($string)
    {
        try {
            $date = new \DateTime($string);
            return $date->getTimeStamp();
        } catch (\Exception $e) {
            return '';
        }
    }
    */
}