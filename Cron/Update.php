<?php
namespace Slavko98\Dev98\Cron;

class Update
{
    protected $scopeConfig;
    protected $pullService;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Slavko98\Dev98\Model\PullService $pullService
        ) {
            $this->scopeConfig = $scopeConfig;
            $this->pullService = $pullService;
    }

    public function execute()
    {
        $isEnabled = $this->scopeConfig->getValue('dev98/general/autoupdate');
        if (!$isEnabled) {
            return $this;
        }

        $this->pullService->execute();
        return $this;
    }
}