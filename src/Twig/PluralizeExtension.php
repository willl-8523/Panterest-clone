<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PluralizeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'doSomething']),
        ];
    }

    public function doSomething(int $count, string $singular, ?string $plural = null)
    {
        // ?string $plural = null => optionnel
        // pluriel != null => {{ pluralize(pins|length, "Pin", "Pins") }}
        // pluriel = null => {{ pluralize(pins|length, "Pin") }}

        // $x = expr1 ?? expr12 retourne la valeur de $x. La valeur de $x est expr1 si expr1 existe et n'est pas nul. Si expr1 n'existe pas ou est nul, la valeur de $ x est expr2

        // Si plural existe => $singular . 's'
        $plural = $plural ?? $singular . 's';
        $str = $count === 1 ? $singular : $plural;

        return "$count $str";
    }
    
    // public function doSomething(int $count, string $singular, string $plural)
    // {
    //     $str = $count === 1 ? $singular : $plural;

    //     return "$count $str";
    // }
}
