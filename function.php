<?php


function required($value)
{
    return !is_null($value) && !empty(trim($value));
}


function validationEmail($value)
{
    if ($value != null && !empty(trim($value))) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    return true;
}


function validationNationalCode($value)
{
    if (preg_match("/^\d{10}$/", $value)) {
        $blacklist = array("0000000000", "1111111111", "2222222222", "3333333333", "4444444444", "5555555555", "6666666666", "7777777777", "8888888888", "9999999999");
        if ((array_search($value, $blacklist)) !== FALSE) {
            return false;
        } else {
            $check = (int)$value[9];
            $sum = array_sum(array_map(function ($x) use ($value) {
                    return ((int)$value[$x]) * (10 - $x);
                }, range(0, 8))) % 11;
            if (($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11)) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

function validateUserName($value)
{
    if (preg_match("/^[a-zA-Z0-9]+$/", $value)) {
        return true;
    }
}

function validMobile($value)
{
    return preg_match('/^09[0-9]{9}$/', $value);
}

function validateName($value)
{
//    return preg_match('/^[^\x{600}-\x{6FF}]+$/u', );
    return preg_match("/^[ آابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی]+$/", $value);
}

function validatePassword($value)
{
    return preg_match("/^[a-z0-9@A-Z]+$/mu", $value);
}

function validateDay($value)
{
//    ۰۱۲۳۴۵۶۷۸۹
    $re = '/^([۱][۰-۹]{3}[\/]([۰][۱-۶])[\/]([۰][۱-۹]|[۱۲][۰-۹]|[۳][۰۱])|[۱][۰-۹]{3}[\/]([۰0][۷-۹]|[۱][۰۱۲])[\/]([۰][۱-۹]|[۱۲][۰-۹]|(۳۰)))$/mu';
    $fa = preg_match($re, $value);
    $re2 = '/^([1-4]\d{3}\/((0[1-6]\/((3[0-1])|([1-2][0-9])|(0[1-9])))|((1[0-2]|(0[7-9])|([1-9]))\/(30|([1-2][0-9])|(0[1-9])|([1-9])))))$/mu';
    $en = preg_match($re2, $value);
    if ($en || $fa) return true;
    return false;
}

function dateDiff($startDate)
{
    list($fdY, $fdM, $fdD) = explode('/', $startDate);
    $fts = jalali_to_gregorian($fdY, $fdM, $fdD, '-');
    $time = new DateTime($fts);
    $current = new DateTime('today');
    var_dump($time->diff($current)->d);
}


function validationImage($file)
{

    $filename = $file["name"];
//    $path = $_FILES['image']['name'];
//    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $fileNameCmps = explode(".", $filename);
    $fileExtension = strtolower(end($fileNameCmps));
    $fileSize = $file['size'];
    $allowedFileExtensions = array('jpg', 'gif', 'png', 'jpeg');
    $check = getimagesize($file["tmp_name"]);


    if ($check === false) {
        return "نوع فایل باید تصویر باشد.";
    } elseif (!in_array($fileExtension, $allowedFileExtensions)) {
        return 'فرمت‌های معتبر فایل عبارتند از: jpg, jpeg, png ,gif';
    } elseif ($fileSize > 800000) {
        return "حد اکثر اندازه تصویر باید 800 کیلوبایت باشد.";
    }
}

function makeEmail($filename, $data)
{
    extract($data);
    ob_start();

    include(ROOT_PATH . $filename . '.php');

    $content = ob_get_contents();
    ob_end_clean();
    return $content;

}


function redirect($location)
{
    header("Location: {$location}");
    die();
}

function clean_input_data($text)
{
    return trim(stripcslashes(htmlspecialchars($text)));
}


function getNowTime($jastDate = '')
{
    $time = new DateTime();
    if ($jastDate == true) {
        $time = $time->format('Y-m-d');
    } else {
        $time = $time->format('Y-m-d H:i:s');
    }
    return $time;

}

function miladiToShamsi($time)
{
    list($fdY, $fdM, $fdD) = explode('/', $time);
    $result = gregorian_to_jalali($fdY, $fdM, $fdD, '/');
    return $result;
}


function create_slug($string, $separator = '-')
{
    if (is_null($string)) {
        return "";
    }

    // Remove spaces from the beginning and from the end of the string
    $string = trim($string);

    // Lower case everything
    // using mb_strtolower() function is important for non-Latin UTF-8 string | more info: https://www.php.net/manual/en/function.mb-strtolower.php
    $string = mb_strtolower($string, "UTF-8");;

    // Make alphanumeric (removes all other characters)
    // this makes the string safe especially when used as a part of a URL
    // this keeps latin characters and arabic charactrs as well
    $string = preg_replace("/[^a-z0-9_\s\-ءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $string);

    // Remove multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);

    // Convert whitespaces and underscore to the given separator
    $string = preg_replace("/[\s_]/", $separator, $string);

    return $string;
}

function getImages($images, $url)
{
    if ($images == null || !file_exists($url.'public/media/' . $images)) {
        return false;
    }
    return true;
}