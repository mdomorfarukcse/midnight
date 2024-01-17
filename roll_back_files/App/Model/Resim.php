<?php

namespace Pemm\Model;

use Pemm\Core\Model as BaseModel;
use PDO;

class Resim extends BaseModel
{
    public function hatakontrol($hataKodu = 0)
    {
        if ($hataKodu != UPLOAD_ERR_OK) {
            switch($hataKodu){
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \Exception('Hata : Dosya boyutu çok yüksek');
                case UPLOAD_ERR_PARTIAL:
                    throw new \Exception('Hata : Dosya yükleme işlemi tamamlanmadı.');
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new \Exception('Hata : Lütfen geçerli bir dosya yükleyiniz.');
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    throw new \Exception('Hata : Yapılandırma sorunu.');
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    throw new \Exception('Hata : Dosya kayıt sorunu.');
                    break;
                default:
                    throw new \Exception('Hata : Dosya yükleme işlemi başarısız.');
                    break;
            }
        }

        return $this;
    }

    public function kaydet($resimVeri, $secenekler, $resimAdi, $resimYolu, $maksimumDosyaBoyutu, $maksimumGenislikYukseklik, $klasorAdi = '')
    {
        $sonuc = [];

        try {

            if (empty($resimVeri)) {
                throw new \Exception('Hata : Resim verileri eksik.');
            }

            $this->hatakontrol($resimVeri['error']);

            if ($this->tmpKontrol($resimVeri['tmp_name']) === false) {
                throw new \Exception('Hatalı image');
            }

            if ($resimVeri['size'] > $maksimumDosyaBoyutu) {
                throw new \Exception('Dosya boyutu maksimum ' . ($maksimumDosyaBoyutu / 1000000). 'MB olabilir.');
            }

            $resimAdi = $this->resimAdi($resimAdi);

            $resimYolu = $resimYolu . $klasorAdi;
            $dizin = $_SERVER['DOCUMENT_ROOT'] . '/' . $resimYolu;

            if (!is_dir($dizin)) {
                if (!mkdir($dizin, 0777, true) && !is_dir($dizin)) {
                    throw new \RuntimeException('Dizin oluşturulamadı. Dizin : ' . $dizin);
                }
            }

            $uzanti = strtolower(pathinfo($resimVeri['name'], PATHINFO_EXTENSION));

            list($genislik, $yukselik) = getimagesize($resimVeri['tmp_name']);

            if (($genislik > $maksimumGenislikYukseklik) || ($yukselik > $maksimumGenislikYukseklik)) {
                throw new \Exception('Dosya boyutları maksimum  ' . $maksimumGenislikYukseklik . 'x' . $maksimumGenislikYukseklik . 'px olabilir');
            }

            $tuval = ($genislik > $yukselik) ? $genislik : $yukselik;
            $yeniGenislik = $genislik;
            $yeniYukselik = $yukselik;

            if (!empty($tuval = @$secenekler['resize'])) {
                if ($genislik > $tuval || $yukselik > $tuval) {
                    $oran = ($genislik > $yukselik) ? ($genislik / $tuval) : ($yukselik / $tuval);
                    $yeniGenislik = $genislik / $oran;
                    $yeniYukselik = $yukselik / $oran;
                } else {
                    $oran = ($genislik > $yukselik) ? ($tuval / $genislik) : ($tuval / $yukselik);
                    $yeniGenislik = $genislik * $oran;
                    $yeniYukselik = $yukselik * $oran;
                }

            }

            $dstX = $dstY = 0;
            $tuvalX = $yeniGenislik;
            $tuvalY = $yeniYukselik;
            if (!empty($secenekler['squareCanvas'])) {
                if ($yeniGenislik > $yeniYukselik) {
                    $dstY = ($yeniGenislik - $yeniYukselik) / 2;
                    $tuvalX = $tuvalY = $yeniGenislik;
                } else {
                    $dstX = ($yeniYukselik - $yeniGenislik) / 2;
                    $tuvalX = $tuvalY = $yeniYukselik;
                }
            }

            $olustur = imagecreatetruecolor($tuvalX, $tuvalY);

            switch ($uzanti) {
                case 'jpg':
                case 'jpeg':
                    $kaynak = imagecreatefromjpeg($resimVeri['tmp_name']);
                    break;
                case 'png':
                    $kaynak = imagecreatefrompng($resimVeri['tmp_name']);
                    break;
                default:
                    throw new \Exception('Desteklenmeyen dosya tipi');
            }

            $color = imagecolorallocate($olustur, 255,255,255);
            imagefilledrectangle ($olustur, 0, 0, $tuvalX,  $tuvalY, $color);
            imagecopyresampled($olustur, $kaynak, $dstX, $dstY,0,0, $yeniGenislik, $yeniYukselik, $genislik, $yukselik);
            imagejpeg($olustur,$dizin . $resimAdi . '.' . $uzanti);


            $sonuc = [
                'ok' => true,
                'resimYolu' => $resimYolu,
                'resimAdi' => $resimAdi,
                'uzanti' => $uzanti
            ];

        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        } catch (\Throwable $e) {
            print_r($e);die;
        }

        return $sonuc;
    }

    public function sil($resimYolu)
    {
        return unlink($_SERVER['DOCUMENT_ROOT'] . $resimYolu);
    }

    public function resimAdi($parametreler)
    {
        $resimAdi = implode('-', $parametreler);
        $resimAdi = str_replace([' ', 'ı', 'İ', 'ç', 'Ç', 'Ü', 'ü', 'Ö', 'ö', 'ş', 'Ş', 'ğ', 'Ğ'],
            ['-', 'i', 'I', 'c', 'C', 'U', 'u', 'O', 'o', 's', 'S', 'g', 'G'], $resimAdi);
        $resimAdi = strtolower($resimAdi);
        $resimAdi = preg_replace('~[^A-Za-z0-9_\-?.!]~','',$resimAdi);

        return $resimAdi;
    }

    public function tmpKontrol($tmp)
    {
        return getimagesize($tmp);
    }
}
