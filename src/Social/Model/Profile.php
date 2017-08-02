<?php
namespace GameShop\Site\Social\Model;


class Profile
{
    protected $uid;
    protected $rawName;
    protected $firstname;
    protected $lastname;
    protected $birthday;
    protected $ageRangeDate;
    protected $ageRangeMin;
    protected $ageRangeMax;
    protected $sex;
    protected $phone;
    protected $emails;
    /**
     * Profile constructor.
     * @param string $uid
     * @param string|null $rawName
     * @param string|null $firstname
     * @param string|null $lastname
     * @param \DateTime|null $birthday
     * @param \DateTime|null $ageRangeDate
     * @param int|null $ageRangeMin
     * @param int|null $ageRangeMax
     * @param null|string $sex
     * @param null|string $phone
     * @param array|null $emails
     */
    public function __construct(
        string $uid,
        ?string $rawName = null,
        ?string $firstname = null,
        ?string $lastname = null,
        ?\DateTime $birthday = null,
        ?\DateTime $ageRangeDate = null,
        ?int $ageRangeMin = null,
        ?int $ageRangeMax = null,
        ?string $sex = null,
        ?string $phone = null,
        array $emails = null
    ) {
        $this->uid = $uid;
        $this->rawName = $rawName;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->birthday = $birthday;
        $this->ageRangeDate = $ageRangeDate;
        $this->ageRangeMin = $ageRangeMin;
        $this->ageRangeMax = $ageRangeMax;
        $this->sex = $sex;
        $this->phone = $phone;
        $this->emails = $emails;
    }
    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }
    /**
     * @return string
     */
    public function getRawName(): ?string
    {
        return $this->rawName;
    }
    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstname;
    }
    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastname;
    }
    /**
     * @return \DateTime|null
     */
    public function birthDay(): ?\DateTime
    {
        return $this->birthday;
    }
    /**
     * @return \DateTime|null
     */
    public function getAgeRange(): ?\DateTime
    {
        return $this->ageRangeDate;
    }
    /**
     * @return int|null
     */
    public function ageRangeMin(): ?int
    {
        return $this->ageRangeMin;
    }
    /**
     * @return int|null
     */
    public function getRangeMax(): ?int
    {
        return $this->ageRangeMax;
    }
    /**
     * @return null|string
     */
    public function getSex(): ?string
    {
        return $this->sex;
    }
    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    /**
     * @return ProfileEmail[]
     */
    public function getEmail(): ?array
    {
        return $this->emails;
    }
}
