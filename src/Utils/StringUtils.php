<?php


namespace App\Utils;

use IntlDateFormatter;

class StringUtils
{
    public static function getInitials(string $value, string $separator = ' '): string
    {
        if ($value === '') {
            return '';
        }
        $first = $value[0];
        $arrayValue = explode($separator, $value);
        if (count($arrayValue) === 1) {
            return strtoupper($first);
        }
        $lastItem = array_pop($arrayValue);
        $last = $lastItem[0];
        return strtoupper($first . $last);
    }

    public static function moedaPorExtenso(string $valor = "0", bool $bolExibirMoeda = true, bool $bolPalavraFeminina = false): string
    {
        $valor = self::removerFormatacaoNumero($valor);
        $singular = null;
        $plural = null;

        if ($bolExibirMoeda) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
        $u = array("", "hum", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        if ($bolPalavraFeminina) {
            if ($valor === '1') {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            } else {
                $u = array("", "hum", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            }
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);

        for ($i = 0, $iMax = count($inteiro); $i < $iMax; $i++) {
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0, $iMax = count($inteiro); $i < $iMax; $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = "";
            if ((int)$valor > 0) {
                $ru = ((int)$valor[1] === 1) ? $d10[$valor[2]] : $u[$valor[2]];
            }
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor === "000") {
                $z++;
            } elseif ($z > 0) {
                $z--;
            }
            if (((int)$t === 1) && ($z > 0) && ($inteiro[0] > 0)) {
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            }
            if ($r) {
                if (($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) {
                    $rt .= (($i < $fim) ? ", " : " e ") . $r;
                } else {
                    $rt .= " " . $r;
                }

            }
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "valor não informado");

    }

    public static function removerFormatacaoNumero(string $strNumero): string
    {

        $strNumero = trim(str_replace("R$", null, $strNumero));
        $strNumero = trim(str_replace(".", ",", $strNumero));

        $vetVirgula = explode(",", $strNumero);
        if (count($vetVirgula) == 1) {
            $acentos = array(".");
            $resultado = str_replace($acentos, "", $strNumero);
            return $resultado;
        } else if (count($vetVirgula) != 2) {
            return $strNumero;
        }

        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr($vetVirgula[1], 0, 2);

        $acentos = array(".");
        $resultado = str_replace($acentos, "", $strNumero);
        $resultado .= "." . $strDecimal;

        return $resultado;

    }

    public static function inteiroPorExtenso(int $valor): string
    {
        return self::moedaPorExtenso((string)$valor, false, true);

    }

    public static function dataPorExtenso(\DateTimeInterface $data): string
    {
        $formatter = new IntlDateFormatter('pt_BR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE);
        return $formatter->format($data);
    }

    public static function mascarar($val, $mask): string
    {
        $masked = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; ++$i) {
            if ($mask[$i] === '#') {
                if (isset($val[$k])) {
                    $masked .= $val[$k++];
                }
            } else if (isset($mask[$i])) {
                $masked .= $mask[$i];
            }
        }
        return $masked;
    }

    public static function camelCaseToSnakeCase(string $value): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }

    public static function replaceWithAssocArray(array $values, string $text): string
    {
        $search = array_keys($values);
        $replace = array_values($values);
        return str_replace($search, $replace, $text);
    }

    public static function sha1Data(...$values): string
    {
        return sha1(implode(separator: '\n', array: $values));
    }

    public static function sha1DataIsValid(string $token, ...$values): bool
    {
        return self::sha1Data(...$values) === $token;
    }

//    public static function sha1FormTdoTokenIsValid(\App\Form\COC\Documentos\FormDto $document): bool
//    {
//        return StringUtils::sha1DataIsValid(
//            $document->getTokenValue(),
//            $document->getGrupo(),
//            $document->getLista(),
//            $document->getTipo(),
//            $document->getPessoa(),
//            $document->getInscricao(),
//            $document->getProcesso()
//        );
//    }


    private function imageToBase64($path): string
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

}