<?php
namespace Custom\Translations\Api;

use Custom\Translations\Api\Data\TranslationsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface TranslationsRepositoryInterface
{
    public function save(TranslationsInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(TranslationsInterface $page);

    public function deleteById($id);
}
