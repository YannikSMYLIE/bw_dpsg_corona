<?php
namespace BoergenerWebdesign\BwDpsgCorona\Utility;

class DateTimeUtility {
    /**
     * Erzeugt aus zwei Strings ein DateTime-Objekt.
     * @param string $date
     * @param string $time
     * @return \DateTime
     * @throws \Exception
     */
    public static function getDatetime(string $date, string $time) : \DateTime {
        $datePattern = self::getDatePattern($date);
        $datetime = \DateTime::createFromFormat($datePattern." H:i", $date." ".$time);
        return $datetime;
    }

    /**
     * Ermittelt das Pattern eines Strings.
     * @param string $date
     * @return string
     * @throws \Exception
     */
    private static function getDatePattern(string $date) : string {
        $delimiter = "";
        if(strpos($date, "-") !== false) {
            $delimiter = "-";
        } else if(strpos($date, ".") !== false) {
            $delimiter = ".";
        }

        if($delimiter) {
            $parts = explode($delimiter, $date);
            if(count($parts) == 3) {
                if(strlen($parts[0]) == 4 && strlen($parts[1]) == 2 && strlen($parts[2]) == 2) {
                    return "Y".$delimiter."m".$delimiter."d";
                } else if(strlen($parts[0]) == 2 && strlen($parts[1]) == 2 && strlen($parts[2]) == 4) {
                    return "d".$delimiter."m".$delimiter."Y";
                }
            }
        }

        throw new \Exception("Es konnte kein gültiges Pattern erkannt werden.");
    }
}
