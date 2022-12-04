<?php

namespace CMDL;

class Util
{
    public static function generateValidIdentifier($s, $additionalchars = '')
    {
        $map = [
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'Ae',
            'Å' => 'A',
            'Æ' => 'AE',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ð' => 'D',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'Oe',
            'Ő' => 'O',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'Ue',
            'Ű' => 'U',
            'Ý' => 'Y',
            'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'ae',
            'å' => 'a',
            'æ' => 'ae',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'd',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'oe',
            'ő' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'ue',
            'ű' => 'u',
            'ý' => 'y',
            'þ' => 'th',
            'ÿ' => 'y',
        ];

        $pattern = '/[' . join('', array_keys($map)) . ']/u';
        if (preg_match_all($pattern, $s, $matches)) {
            $c = count($matches[0]);
            for ($i = 0; $i < $c; $i++) {
                $char = $matches[0][$i];
                if (isset($map[$char])) {
                    $s = str_replace($char, $map[$char], $s);
                }
            }
        }

        $s            = strtolower($s);
        $allowedchars = preg_quote('abcdefghijklmnopqrstuvwxyz0123456789_') . preg_quote($additionalchars);

        $patterns = "/[^" . $allowedchars . "]*/";

        return preg_replace($patterns, "", $s);
    }


    /**
     *
     * @param string $s
     * @param string $leftchar
     * @param string $rightchar
     *
     * @return array
     *
     * @link: http://weblogtoolscollection.com/regex/regex.php
     */
    public static function getTextBetweenChars($s, $leftchar, $rightchar)
    {

        // Everything between two i "/i[^i]*i/";
        $leftchar  = preg_quote($leftchar, "/");
        $rightchar = preg_quote($rightchar, "/");
        $pattern   = "/" . $leftchar . "[^" . $leftchar . $rightchar . "]*" . $rightchar . "/";

        preg_match_all($pattern, $s, $matches);

        if (count($matches[0]) == 0) {
            return false;
        } else {
            $result = [];
            foreach ($matches[0] as $match) {
                $result[] = trim(substr($match, 1, -1));
            }

            return $result;
        }
    }


    public static function removeTextBetweenCharsIncludingDelimiters($s, $leftchar, $rightchar)
    {

        $leftchar  = preg_quote($leftchar, "/");
        $rightchar = preg_quote($rightchar, "/");
        $pattern   = "/" . $leftchar . "[^" . $leftchar . $rightchar . "]*" . $rightchar . "/";

        // replace matches with empty space
        $result = preg_filter($pattern, " ", $s);
        if ($result) {
            // and filter double whitespace afterwards (otherwise the upper pattern doesn't get matches without preceeding chars!?
            $result = preg_replace('/\s+/', ' ', $result);

            return $result;
        }

        return $s;
    }


    public static function extractLists($s, $forceNumericalIndex = false)
    {
        $lists = [];

        $result = self::getTextBetweenChars($s, "(", ")");
        if ($result) {
            foreach ($result as $csv) {
                $items    = [];
                $csvitems = explode(',', $csv);

                $i = 1;
                foreach ($csvitems as $item) {
                    $item     = trim($item);
                    $keyvalue = explode(':', $item);
                    if (count($keyvalue) == 2) {
                        $key   = trim($keyvalue[0]);
                        $value = trim($keyvalue[1]);
                    } else {
                        $key   = $item;
                        $value = $item;
                    }

                    if ($forceNumericalIndex) {
                        $key = $i;
                    }

                    // remove surrounding quotes
                    if (substr($key, 0, 1) == '"') {
                        $key = trim($key, '"');
                    } else {
                        $key = trim($key, "'");
                    }

                    if (substr($value, 0, 1) == '"') {
                        $value = trim($value, '"');
                    } else {
                        $value = trim($value, "'");
                    }

                    $items[$key] = $value;
                    $i++;
                }

                if (count($items) == 1) {
                    $item = reset($items);
                    if ($item == '') {
                        $items = [];
                    }
                }

                $lists[] = $items;
            }
        }

        return $lists;
    }


    /**
     *
     * @param string $s
     *
     * @link http://stackoverflow.com/questions/366202/regex-for-splitting-a-string-using-space-when-not-surrounded-by-single-or-double
     */
    public static function extractParams($s)
    {
        $s = self::removeTextBetweenCharsIncludingDelimiters($s, '(', ')');

        // remove double and leading/following spaces
        $s = preg_replace('/\s{2,}/', ' ', $s);
        $s = trim($s);

        $pattern = "/[^\\s\"']+|\"[^\"]*\"|'[^']*'/";
        preg_match_all($pattern, $s, $matches);

        $params = [];
        foreach ($matches[0] as $match) {
            if (substr($match, 0, 1) == '"') {
                $match = trim($match, '"');
            } else {
                $match = trim($match, "'");
            }

            $params[] = $match;
        }

        return $params;
    }


    /**
     * Replaces {{property}} strings with a matching property value
     *
     * @param $properties
     * @param $pattern
     *
     * @link http://stackoverflow.com/questions/15773349/replacing-placeholder-variables-in-a-string
     *
     * @return mixed
     */
    public static function applyNamingPattern($properties, $pattern)
    {
        preg_match_all('^{{(.+?)}}^', $pattern, $matches);

        if (isset($matches[1]) && count($matches[1]) > 0) {
            foreach ($matches[1] as $value) {
                if (array_key_exists($value, $properties)) {
                    $pattern = preg_replace("/\{\{$value\}\}/", $properties[$value], $pattern);
                } else {
                    $pattern = preg_replace("/\{\{$value\}\}/", '', $pattern);
                }
            }
        }

        return $pattern;
    }
}
