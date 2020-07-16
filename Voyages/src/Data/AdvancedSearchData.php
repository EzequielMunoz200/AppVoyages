<?php


namespace App\Data;

use Symfony\Component\Validator\Constraints as Assert;


class AdvancedSearchData
{
    /**
     * @var string
     */

    private $country = '';


    /**
     * Undocumented variable
     *
     * @var datetime
     */
    private $startDate;

    /**
     * Undocumented variable
     *
     * @var datetime
     */
    private $endDate;



    /**
     * Undocumented variable
     *
     * @var string
     */
    private $landscape; 


    /**
     *
     * @var array
     */
    private $activities = [
        'ski',
        'escalade',
        'via ferrata' 
    ];

    /**
     *
     * @var array
     */
    private $placesToVisit = [];

    /**
     * @var null/string
     * @Assert\Choice({"Francophone", "Non francophone"})
     */
    private $spokenLanguage;


    /**
     * Get the value of country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }



    /**
     * Get the value of placesToVisit
     *
     * @return  array
     */
    public function getPlacesToVisit()
    {
        return $this->placesToVisit;
    }

    /**
     * Set the value of placesToVisit
     *
     * @param  array  $placesToVisit
     *
     * @return  self
     */
    public function setPlacesToVisit(array $placesToVisit)
    {
        $this->placesToVisit = $placesToVisit;

        return $this;
    }

    /**
     * Get the value of spokenLanguage
     *
     * @return  null/string
     */
    public function getSpokenLanguage()
    {
        return $this->spokenLanguage;
    }

    /**
     * Set the value of spokenLanguage
     *
     * @param  null/string  $spokenLanguage
     *
     * @return  self
     */
    public function setSpokenLanguage(?string $spokenLanguage)
    {
        $this->spokenLanguage = $spokenLanguage;

        return $this;
    }



    /**
     * Get the value of activities
     *
     * @return  array
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Set the value of activities
     *
     * @param  array  $activities
     *
     * @return  self
     */
    public function setActivities(array $activities)
    {
        $this->activities = $activities;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  array
     */ 
    public function getLandscape()
    {
        return $this->landscape;
    }

    /**
     * Set undocumented variable
     *
     * @param  array  $landscape  Undocumented variable
     *
     * @return  self
     */ 
    public function setLandscape(array $landscape)
    {
        $this->landscape = $landscape;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  datetime
     */ 
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set undocumented variable
     *
     * @param  datetime  $startDate  Undocumented variable
     *
     * @return  self
     */ 
    public function setStartDate(?\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  datetime
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set undocumented variable
     *
     * @param  datetime  $endDate  Undocumented variable
     *
     * @return  self
     */ 
    public function setEndDate(?\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
}
