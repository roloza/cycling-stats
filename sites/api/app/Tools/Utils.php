<?php

namespace App\Tools;

class Utils {

    public static function Download($url, $type, $options = []) {
        $postvars = '';
        foreach($options as $key => $value) {
            $postvars .= $key . "=" . $value . "&";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($type == 1) {
            curl_setopt($ch, CURLOPT_POST, $type);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $response = curl_exec ($ch);
        $err = curl_error($ch);  //if you need
        curl_close ($ch);
        return $response;
    }

    public static function parseCompetitionDate($date) {
        $date = str_replace("/Date(", "", $date);
        $date = (int)str_replace("000)/", "", $date) + 7200;
        return [
            'date'  => date('Y-m-d H:i:s', $date),
            'year'  => date('Y', $date)
        ];
    }

    public static function readAsList($file) {
        $handle = fopen($file, "r");
        $fileHeader = [];
        $results = [];
        if ($handle) {
            $cpt = -1;
            while (($line = fgets($handle)) !== false) {
                $line = str_replace(["\n", "\r"], '', $line);
                $items = explode(',', $line);
                if ($cpt < 0) {
                    $fileHeader = $items;
                } else {
                    foreach ($items as $k => $item) {
                        $results[$cpt][$fileHeader[$k]] = trim($item);
                    }

                }
                $cpt++;

            }

            fclose($handle);
        } else {
            // error opening the file.
        }
        return $results;
    }
}
