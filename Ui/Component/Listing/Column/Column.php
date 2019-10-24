<?php
namespace Custom\Translations\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class Column extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     * Column constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    public function prepare()
    {
        $config = $this->getData('config');
        $this->setData('config', $config);

        parent::prepare();
    }
}
