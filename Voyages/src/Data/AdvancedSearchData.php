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
     * @var array
     */
    private $tags = [];




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
     * Get undocumented variable
     *
     * @return  array
     */ 
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set undocumented variable
     *
     * @param  array  $tags  Undocumented variable
     *
     * @return  self
     */ 
    public function setTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }
}