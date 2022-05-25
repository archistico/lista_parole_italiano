<?php
$ris = [];

$car_validi = [
    "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p",
    "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "à", "è", "é", "ì", "ò", "ù"
];

function my_split($string, $split_length = 1)
{
    if ($split_length == 1) {
        return preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY);
    } elseif ($split_length > 1) {
        $return_value = [];
        $string_length = mb_strlen($string, "UTF-8");
        for ($i = 0; $i < $string_length; $i += $split_length) {
            $return_value[] = mb_substr($string, $i, $split_length, "UTF-8");
        }
        return $return_value;
    } else {
        return false;
    }
}

$handle = fopen("testi/test.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $linea_pulita = strtolower(preg_replace("/\r|\n/", "", $line));
        $linea_pulita = str_replace("È", "è", $linea_pulita);
        $linea_pulita = trim(str_replace(['’', "'", '.', ',', ':', ';', '?', '!', '”', '“', '«', '»', '"', '…', '–', '‘'], " ", $linea_pulita));
        $arr = explode(" ", $linea_pulita);

        foreach($arr as $a) {
            $str = trim($a);
            
            if ($str == "") {
                continue;
            }

            $parola = my_split($str);

            // Se $str contiene caratteri differenti da $car_validi viene esclusa
            if(count(array_diff($parola, $car_validi)) > 0 ) {
                // echo $str."\n";              
                continue;
            }

            $ris[] = $str;
        }
    }

    fclose($handle);
}

echo "Parole: " . count($ris) ."\n";

// Verifico che su parole.db ci sia la parola
// se c'è incremento di uno la quantita

$dbh = new \PDO('sqlite:parole.db');
foreach ($ris as $p) {
    try {
        $stmt = $dbh->prepare('SELECT count(*) from parole WHERE parola = :parola');
        $stmt->bindParam(':parola', $p, SQLITE3_TEXT);
        $stmt->execute();

        $count = $stmt->fetchColumn();
        if($count > 0) {
            echo "Parola presente: {$p}\n";
        } else {
            echo "Attenzione non presente: {$p}\n";
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
$dbh = null;
