<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Helpers
{
    /**
     * Ajout de nouveau fichier
     *
     * @param $file
     * @param string $dirName
     * @return string[]
     */
    public static function addFile($file, string $dirName): array
    {
        try {
            //$path = storage_path() . DIRECTORY_SEPARATOR . $dirName;
            $path = public_path() . DIRECTORY_SEPARATOR . $dirName;

            if(is_dir($path) === false)
            {
                mkdir($path, 0777, true);
            }

            $fileName = date('Ymd.His') . '-' . $file->getClientOriginalName();
            $fileName = str_replace(' ', '-', $fileName);
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName = md5($fileName) . '.' . $fileType;
            $file->move($path, DIRECTORY_SEPARATOR . $fileName);

            return ['path' => $path, 'fileName' => $fileName];
        } catch (\Exception $e) {
            dump($e->getMessage());
            die();
        }
    }

    /**
     * @param array $table1
     * @param array $table2
     * @return array
     * Compare 2 tableaux
     */
    public static function compareTables(array $table1, array $table2): array
    {
        return array_diff($table1, $table2);
    }

    /**
     * @param $sms
     * @param $phone
     * @return \Illuminate\Http\Client\Response
     *
     * Fonction d'envoie de SMS
     */
    public static function send_sms($sms, $phone)
    {
        try {
            Log::info('Helper SMS envoyé ' . $sms);
            Log::info('Helper num recepteur ' . $phone);

            $args = array(
                'username' => 'cinetpay',
                'password' => 'SHESk1gG222',
                'sender' => '22586446316',
                'text' => $sms,
                'type' => 'text',
                'to' => $phone
            );

            return Http::asForm()->post('http://admin.smspro24.com/api/api_http.php', $args);
        } catch (\Exception $e) {
            Log::error("Erreur " . $e->getMessage());
        }
    }

    public static function formatPhoneNumber($phone)
    {
        return '225'.$phone;
    }

    public static function generateRandomCode(): string
    {
        return 'E-'.mt_rand(00000, 99999);
    }

    public static function randomUniqString($length = 7) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return uniqid("E").'.'.$randomString.'.'.date("YmdHis");
    }

    public static function cleanString($text) {
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
            '/[“”«»„]/u'    =>   ' ', // Double quote
            '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    /**
     * @param $name
     * @return string
     * Genreate unique slug for levels or other
     */
    public static function generateCodeByName($name): string
    {
        return strtoupper(substr(md5(uniqid($name, true)), 0, 6));
    }

    public static function getYearOfDate($date_debut, $date_fin)
    {
        $debut = substr($date_debut, 0, 4);
        $fin = substr($date_fin, 0, 4);

        return $debut."-".$fin;
    }
}
