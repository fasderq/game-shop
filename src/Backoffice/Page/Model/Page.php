<?php
namespace GameShop\Site\Backoffice\Page\Model;

/**
 * Class Page
 * @package GameShop\Site\Backoffice\Page\Model
 */
class Page
{
    protected $code;
    protected $title;
    protected $parentId;
    protected $content;
    protected $keywords;
    protected $position;
    protected $isActive;

    /**
     * Page constructor.
     * @param string $code
     * @param string $title
     * @param int|null $parentId
     * @param null|string $content
     * @param null|string $keywords
     * @param int $position
     * @param bool $isActive
     */
    public function __construct(
        string $code,
        string $title,
        ?int $parentId = null,
        ?string $content = null,
        ?string $keywords =null,
        int $position = 0,
        bool $isActive = true
    ) {
        $this->code = $code;
        $this->title = $title;
        $this->parentId = $parentId;
        $this->content = $content;
        $this->keywords = $keywords;
        $this->position = $position;
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return null|string
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
