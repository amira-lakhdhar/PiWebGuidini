<?php

namespace App\Services;


use Endroid\QrCode\Factory\QrCodeFactoryInterface;
use Endroid\QrCode\Writer\FpdfWriter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\QrCode;
use Symfony\Component\Validator\Constraints\Uuid;


class QrcodeService
{


    public function __construct()
    {
    }

    public function qrcodes($query)
    {
        $writer = new PngWriter();

        $url = 'http://127.0.0.1:8000/Verif/';

        $qrcode=new QrCode($url.$query);

        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');

        $path = 'C:\Users\Toumi\Desktop\Esprit\Symfony\Chaima\Pi\public\QrCode';

        // set qrcode
        $qrcode->setEncoding('UTF-8');
        $qrcode->setSize(300);
        $qrcode->setMargin(10);
        $qrcode->setWriter($writer);




    $qrcodename='qrcode'.uniqid().'.png';
        $writer->writeFile($qrcode,$path.'\img-'.$qrcodename);
        $qrcodename='img-'.$qrcodename;


        return $qrcodename;
    }
}