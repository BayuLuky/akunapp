<?php

use Intervention\Image\Facades\Image;
// use Image;

function secureToken()
{
    return hash('sha256', mt_rand(10000, 99999) . time() . md5(time()));
}

function secretNumber()
{
    return mt_rand(10000, 99999) . time();
}

function uniquecode()
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);
}

function clean($string = null)
{
    return ($string) ? strip_tags($string) : '';
}

function stamp()
{
    return date('Y-m-d H:i:s');
}

function isImage($file = NULL)
{
    $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
    $contentType = mime_content_type($file);

    if (!in_array($contentType, $allowedMimeTypes)) {
        return 0;
    } else {
        return 1;
    }
}

function imgSizes()
{
    return [
        'thumbnail' => [
            150, 150
        ],
        'medium' => [
            300, 300
        ],
        'medium_large' => [
            768, 768
        ],
        'large' => [
            1024, 1024
        ],
    ];
}

function doUpload($data = NULL)
{
    if ($data == NULL) {
        return FALSE;
    } else {
        if (!is_array($data)) {
            return FALSE;
        } else {
            $msg = '';

            $file = (isset($data['file'])) ? $data['file'] : $msg .= '<p>File not found.</p>';
            $path = (isset($data['path'])) ? $data['path'] : $msg .= '<p>Path is not defined.</p>';
            $type = (isset($data['allow_type'])) ? $data['allow_type'] : '';
            $size = (isset($data['allow_size'])) ? $data['allow_size'] : '';

            if ($msg != '') {
                return $msg;
            } else {

                $fileOriginalName = $file->getClientOriginalName();
                $fileExtension = $file->guessExtension();
                $fileSize = $file->getSize();
                $fileRealPath = $file->getRealPath();

                $isImage = isImage($fileRealPath);

                if ($type != '') {
                    $allowedType = explode('|', $type);
                    if (!in_array(mime_content_type($fileRealPath), $allowedType)) {
                        return [
                            'response' => 'error',
                            'code' => 406,
                            'message' => '<p>File yang anda upload tidak diizinkan.</p>'
                        ];

                        exit;
                    }
                }

                if ($size != '') {
                    $allowedSize = $size * 1000;
                    if ($fileSize > $allowedSize) {
                        return [
                            'response' => 'error',
                            'code' => 406,
                            'message' => '<p>File yand anda upload melebihi batas maksimal yang diizinkan.</p>'
                        ];

                        exit;
                    }
                }

                $newFileName = date('Ymd') . '_' . md5(uniqid(time() . $fileOriginalName, true));

                $imgSize = imgSizes();
                $otherSizes = [];

                foreach ($imgSize as $key => $val) {
                    $image = $data['file'];
                    $imageName = $newFileName . '_' . $key . '.' . $fileExtension;
                    $filePath = 'uploads';

                    $img = Image::make($image->path());
                    $img->resize($val[0], $val[1], function ($const) {
                        $const->aspectRatio();
                    })->save($filePath . '/' . $imageName);

                    $otherSizes[] = [
                        'originName' => $image->getClientOriginalName(),
                        'randomName' => $imageName,
                        'path' => URL::to('/') . '/' . $path . $imageName,
                        'ext' => $image->guessExtension(),
                        'size' => $image->getSize(),
                        'isImage' => $isImage
                    ];
                }

                $file->move($path, $newFileName . '.' . $fileExtension);

                return [
                    'response' => 'ok',
                    'code' => 200,
                    'message' => '<p>File berhasil di upload.</p>',
                    'data' => [
                        'originName' => $fileOriginalName,
                        'randomName' => $newFileName,
                        'path' => URL::to('/') . '/' . $path . $newFileName,
                        'ext' => $fileExtension,
                        'size' => $fileSize,
                        'isImage' => $isImage,
                        'otherSizes' => $otherSizes
                    ]
                ];
            }
        }
    }
}

function limitChar($string = null, $limit = 100)
{
    if (strlen($string) > $limit) {

        $stringCut = substr($string, 0, $limit);
        $endPoint = strrpos($stringCut, ' ');

        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '...';
    }
    return $string;
}

function splitMonth($date = null, $lang = 'en')
{
    if ($date) {
        $en = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $id = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agus', 'sep', 'okt', 'nov', 'des'];

        $month = date('m', strtotime($date));

        switch ($month) {
            case $month:
                return ($lang == 'en') ? $en[$month - 1] : $id[$month - 1];
                break;
            default:
                return '-';
        }
    } else {
        return $date;
    }
}
