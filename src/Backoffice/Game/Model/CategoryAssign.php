<?php
namespace GameShop\Site\Backoffice\Game\Model;

/**
 * Class CategoryAssign
 * @package GameShop\Site\Backoffice\Game\Model
 */
class CategoryAssign
{
    protected $categoryId;
    protected $categoryName;

    /**
     * CategoryAssign constructor.
     * @param int $categoryId
     * @param string $categoryName
     */
    public function __construct(
        int $categoryId,
        string $categoryName
    ) {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }
}
