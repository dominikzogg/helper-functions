<?php

namespace Dominikzogg\StringHelpers;

/**
 * Standardize a parameter (strip special characters and convert spaces)
 * @param string
 * @param boolean
 * @return string
 */
function standardize($strString)
{
    $arrSearch = array('/[^a-zA-Z0-9 _-]+/', '/ +/', '/\-+/');
    $arrReplace = array('', '-', '-');

    $strString = \html_entity_decode($strString, ENT_QUOTES, 'utf-8');
    $strString = replaceUmlauts($strString);
    $strString = \preg_replace($arrSearch, $arrReplace, $strString);
    $strString = \strtolower($strString);

    return \trim($strString, '-');
}

/**
 * @param string $string
 * @param integer $wishedLength
 * @param string $suffix
 * @return string
 */
function shorten($string, $wishedLength, $suffix = '')
{
    $length = strlen($string);
    if ($length <= $wishedLength) {
        return $string;
    }

    $wishedString = '';

    $matches = array();
    preg_match_all('/\S+/', $string, $matches);

    foreach ($matches[0] as $word) {
        $wishedString .= $word;
        if (strlen($wishedString) < $wishedLength) {
            $wishedString .= ' ';
        } else {
            break;
        }
    }

    return rtrim($wishedString) . $suffix;
}

/**
 * @param $input
 * @return string
 */
function underscoreToCamelCase($input)
{
    $output = '';
    $inputParts = explode('_', $input);

    if(1 === count($inputParts)) {
        return lcfirst($input);
    }

    foreach ($inputParts as $i => $inputPart) {
        $inputPart = strtolower($inputPart);
        if (0 !== $i) {
            $output .= ucfirst($inputPart);
        } else {
            $output .= lcfirst($inputPart);
        }
    }

    return $output;
}

/**
 * @param $input
 * @return string
 */
function camelCaseToUnderscore($input)
{
    $output = '';
    $inputParts = preg_split('/(?=[A-Z])/', $input);
    foreach ($inputParts as $inputPart) {
        if ('' !== $inputPart) {
            $output .= rtrim($inputPart . '_', '_') . '_';
        }
    }

    return strtolower(substr($output, 0, -1));
}

/**
 * @param int|float $a
 * @param int|float $b
 * @return int
 * @throws \InvalidArgumentException
 */
function numberCmp($a, $b)
{
    if (!is_numeric($a)) {
        throw new \InvalidArgumentException('A is not a number!');
    }

    if (!is_numeric($b)) {
        throw new \InvalidArgumentException('B is not a number!');
    }

    if ($a == $b) {
        return 0;
    }

    return $a > $b ? 1 : -1;
}

/**
 * @param string $input
 * @return string
 */
function replaceUmlauts($input)
{
    $signs = array(
        'A' => array('À','Á','Â','Ã','Ä','Å','Æ'),
        'C' => array('Ç'),
        'D' => array('Ð'),
        'E' => array('È','É','Ê','Ë'),
        'I' => array('Ì','Í','Î','Ï'),
        'N' => array('Ñ'),
        'O' => array('Ò','Ó','Ô','Õ','Ö'),
        'TH' => array('Þ'),
        'U' => array('Ù','Ú','Û','Ü'),
        'Y' => array('Ý'),

        'a' => array('à','á','â','ã','ä','å','æ'),
        'c' => array('ç'),
        'd' => array('ð'),
        'e' => array('è','é','ê','ë'),
        'i' => array('ì','í','î','ï'),
        'n' => array('ñ'),
        'o' => array('ò','ó','ô','õ','ö'),
        'ss' => array('ß'),
        'th' => array('þ'),
        'u' => array('ù','ú','û','ü'),
        'y' => array('ý','ÿ'),
    );

    foreach ($signs as $sign => $umlauts) {
        foreach ($umlauts as $umlaut) {
            $input = str_replace($umlaut, $sign, $input);
        }
    }

    return $input;
}
