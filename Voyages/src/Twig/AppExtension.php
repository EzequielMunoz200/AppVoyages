<?php

namespace App\Twig;


//https://stackoverflow.com/questions/18933753/twig-sum-row-above-the-summerisation
//https://symfony.com/doc/current/templating/twig_extension.html

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('sum', 'array_sum'),
        ];
    }

    /* public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    } */
}