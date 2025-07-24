<?php

class ReceiptUtils {
    /**
     * Convertit un nombre en lettres en français
     */
    public static function numberToWords($number) {
        $dictionary = [
            0 => 'zéro',
            1 => 'un',
            2 => 'deux',
            3 => 'trois',
            4 => 'quatre',
            5 => 'cinq',
            6 => 'six',
            7 => 'sept',
            8 => 'huit',
            9 => 'neuf',
            10 => 'dix',
            11 => 'onze',
            12 => 'douze',
            13 => 'treize',
            14 => 'quatorze',
            15 => 'quinze',
            16 => 'seize',
            17 => 'dix-sept',
            18 => 'dix-huit',
            19 => 'dix-neuf',
            20 => 'vingt',
            30 => 'trente',
            40 => 'quarante',
            50 => 'cinquante',
            60 => 'soixante',
            70 => 'soixante-dix',
            80 => 'quatre-vingt',
            90 => 'quatre-vingt-dix',
            100 => 'cent',
            1000 => 'mille',
            1000000 => 'million',
            1000000000 => 'milliard'
        ];

        if ($number < 0) {
            return 'moins ' . self::numberToWords(abs($number));
        }

        if ($number < 21) {
            return $dictionary[$number];
        }

        if ($number < 100) {
            $tens = floor($number / 10) * 10;
            $units = $number % 10;
            if ($units == 0) {
                return $dictionary[$tens];
            }
            if ($units == 1 && $tens != 80) {
                return $dictionary[$tens] . ' et un';
            }
            return $dictionary[$tens] . '-' . $dictionary[$units];
        }

        if ($number < 1000) {
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            if ($hundreds == 1) {
                $text = 'cent';
            } else {
                $text = $dictionary[$hundreds] . ' cents';
            }
            if ($remainder > 0) {
                $text .= ' ' . self::numberToWords($remainder);
            }
            return $text;
        }

        if ($number < 1000000) {
            $thousands = floor($number / 1000);
            $remainder = $number % 1000;
            if ($thousands == 1) {
                $text = 'mille';
            } else {
                $text = self::numberToWords($thousands) . ' mille';
            }
            if ($remainder > 0) {
                $text .= ' ' . self::numberToWords($remainder);
            }
            return $text;
        }

        if ($number < 1000000000) {
            $millions = floor($number / 1000000);
            $remainder = $number % 1000000;
            $text = self::numberToWords($millions) . ' million';
            if ($millions > 1) {
                $text .= 's';
            }
            if ($remainder > 0) {
                $text .= ' ' . self::numberToWords($remainder);
            }
            return $text;
        }

        $billions = floor($number / 1000000000);
        $remainder = $number % 1000000000;
        $text = self::numberToWords($billions) . ' milliard';
        if ($billions > 1) {
            $text .= 's';
        }
        if ($remainder > 0) {
            $text .= ' ' . self::numberToWords($remainder);
        }
        return $text;
    }

    /**
     * Calcule le montant du prochain versement
     */
    public static function calculerProchainVersement($montantTotal, $montantPaye, $nombreTranches) {
        if ($nombreTranches <= 1) {
            return 0;
        }
        $resteAPayer = $montantTotal - $montantPaye;
        return ceil($resteAPayer / ($nombreTranches - 1));
    }

    /**
     * Calcule la date du prochain versement
     */
    public static function calculerDateProchainVersement($dateInscription, $nombreTranches) {
        if ($nombreTranches <= 1) {
            return null;
        }
        return date('Y-m-d', strtotime($dateInscription . ' +3 months'));
    }

    /**
     * Génère un numéro de reçu au format R suivi de 5 chiffres
     * @param int $id_inscription L'ID de l'inscription
     * @return string Le numéro de reçu formaté
     */
    public static function genererNumeroRecu($id_inscription) {
        // Formater l'ID avec des zéros devant pour avoir 5 chiffres
        $numero = str_pad($id_inscription, 5, '0', STR_PAD_LEFT);
        return 'R' . $numero;
    }
} 