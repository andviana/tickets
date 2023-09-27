<?php

namespace App\Twig;

use App\Utils\StringUtils;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppStringExtension extends AbstractExtension
{
    public const LETTER = [' ', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

    public function getFilters(): array
    {
        return [
            new TwigFilter('get_initials_name', [$this, 'getInitials']),
            new TwigFilter('get_first_word', [$this, 'getFirstWord']),
            new TwigFilter('get_last_word_from_url', [$this, 'getLastWordFromUrl']),
            new TwigFilter('get_letter_from_number', [$this, 'getLetterFromNumber']),
            new TwigFilter('get_format_cpf', [$this, 'getFormatCpf']),
            new TwigFilter('get_format_cnpj', [$this, 'getFormatCnpj']),
            new TwigFilter('get_format_cep', [$this, 'getFormatCEP']),
            new TwigFilter('get_format_telefone', [$this, 'getFormatTelefone']),
            new TwigFilter('get_format_sexo', [$this, 'getFormatSexo']),
            new TwigFilter('get_format_boolean', [$this, 'getFormatBoolean']),
            new TwigFilter('get_format_money', [$this, 'getFormatMoney']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_initials_name', [$this, 'getInitials']),
            new TwigFunction('get_first_word', [$this, 'getFirstWord']),
            new TwigFunction('get_last_word_from_url', [$this, 'getLastWordFromUrl']),
            new TwigFunction('get_letter_from_number', [$this, 'getLetterFromNumber']),
            new TwigFunction('get_format_cpf', [$this, 'getFormatCpf']),
            new TwigFunction('get_format_cnpj', [$this, 'getFormatCnpj']),
            new TwigFunction('get_format_cep', [$this, 'getFormatCEP']),
            new TwigFunction('get_format_telefone', [$this, 'getFormatTelefone']),
            new TwigFunction('get_format_sexo', [$this, 'getFormatSexo']),
            new TwigFunction('get_format_boolean', [$this, 'getFormatBoolean']),
            new TwigFunction('get_format_money', [$this, 'getFormatMoney']),
        ];
    }

    public function getInitials($value): string
    {
        return StringUtils::getInitials($value);
    }

    public function getFirstWord($value): string
    {
        return explode(' ', $value)[0];
    }

    public function getLastWordFromUrl($value): ?string
    {
        $elemento = (explode('\\', $value));
        return array_pop($elemento);
    }

    public function getLetterFromNumber($value): string
    {
        return self::LETTER[$value];
    }


    public function getFormatCpf($value): string
    {
        if ($value !== null && strlen($value) === 11) {
            return StringUtils::mascarar($value, '###.###.###-##');
        }
        return $value;
    }

    public function getFormatCnpj($value): string
    {
        if ($value !== null && strlen($value) === 14) {
            return StringUtils::mascarar($value, '##.###.###/####-##');
        }
        return $value;
    }

    public function getFormatCEP($value): string
    {
        if ($value !== null && strlen($value) === 8) {
            return StringUtils::mascarar($value, '#####-###');
        }
        return $value;
    }

    public function getFormatTelefone($value): ?string
    {
        if ($value !== null && (strlen($value) > 7 && strlen($value) < 12)) {
            return match (strlen($value)) {
                8 => StringUtils::mascarar($value, '####-####'),
                9 => StringUtils::mascarar($value, '#####-####'),
                10 => StringUtils::mascarar($value, '(##) ####-####'),
                11 => StringUtils::mascarar($value, '(##) #####-####'),
                default => $value,
            };
        }
        return $value;
    }

    public function getFormatSexo($value): string
    {
        return match ($value) {
            'M' => 'MASCULINO',
            'F' => 'FEMININO',
            default => $value
        };
    }

    public function getFormatBoolean($value): string
    {
        return $value
            ? 'bi bi-circle-fill text-success'
            : 'bi bi-circle-fill text-secondary text-opacity-50';
    }

    public function getFormatMoney($value)
    {
        return "R$ " . number_format($value, 2, ",", ".");
    }

}