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

$file = "testo (2)";
$handle = fopen("testi/{$file}.txt", "r");
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

echo "Parole in totale: " . count($ris) ."\n";

sort($ris);
$parole_differenti = array_unique($ris);
sort($parole_differenti);
echo "Parole differenti: " . count($parole_differenti) ."\n";

// creo array con parola, conteggio
$parole_conteggio = array_count_values($ris);
arsort($parole_conteggio);

$file_risultato = fopen("risultato_parole_{$file}.txt", "w") or die("Unable to open file nuove_parole!");
foreach ($parole_conteggio as $p => $v) {
    fwrite($file_risultato, "{$p}, {$v}\n");
}
fclose($file_risultato);

$dbh = new \PDO('sqlite:parole.db');

$file_nuove_parole = fopen("nuove_parole_{$file}.txt", "w") or die("Unable to open file nuove_parole!");

$conteggio = 0;
foreach ($parole_differenti as $p) {
    try {
        $conteggio++;

        $stmt = $dbh->prepare("SELECT count(*) from parole WHERE parola = '{$p}'");
        $stmt->execute();

        $count = $stmt->fetchColumn();
        if($count == 0) {
            //echo "[{$conteggio}] Non presente: {$p}\n";
            fwrite($file_nuove_parole, "{$p}\n");
            echo "{$p}\n";
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
$dbh = null;
fclose($file_nuove_parole);