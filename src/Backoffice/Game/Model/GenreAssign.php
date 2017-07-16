<?php
namespace GameShop\Site\Backoffice\Game\Model;

/**
 * Class GenreAssign
 * @package GameShop\Site\Backoffice\Game\Model
 */
class GenreAssign
{
    protected $genreId;
    protected $genreName;

    /**
     * GenreAssign constructor.
     * @param int $genreId
     * @param string $genreName
     */
    public function __construct(
        int $genreId,
        string $genreName
    ) {
        $this->genreId = $genreId;
        $this->genreName = $genreName;
    }

    /**
     * @return int
     */
    public function getGenreId(): int
    {
        return $this->genreId;
    }

    /**
     * @return string
     */
    public function getGenreName(): string
    {
        return $this->genreName;
    }
}
