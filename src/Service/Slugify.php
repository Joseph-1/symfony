<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $characterSpe = [
            'à', 'á', 'â', 'ã', 'ä', 'å', 'æ',
            'ç', 'è', 'é', 'ê', 'ë',
            'ì','í', 'î', 'ï',
            'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'œ',
            'š', 'Þ', 'ù', 'ú', 'û', 'ü',
            'ý', 'ÿ',
        ];

        $charachterSpeReplace = [
            'a', 'a','a','a','a','a','a',
            'c', 'e', 'e', 'e', 'e',
            'i','i', 'i', 'i',
            'o', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            's', 'p', 'u', 'u', 'u', 'u',
            'y', 'y',
        ];

        $input = str_replace($characterSpe, $charachterSpeReplace, $input);

        $input = trim($input);
        $input = str_replace(' ', '-', $input);
        $input = strtolower($input);

        return $input;

    }
}