<?php

namespace App\Util;

class DataValidate
{
    public static function maiorQueZero(float $number, $abort = false):bool|array|\Illuminate\Http\JsonResponse
    {
        if ($number<=0 && $abort) return response()->json(['success'=>false,'message'=>'Valor não é maior zero'],422);
        if ($number<=0 && !$abort) return false;
        return true;
    }
    public static function menorQueZero(float $number, $abort = false):bool|array|\Illuminate\Http\JsonResponse
    {
        if ($number>=0 && $abort) return response()->json(['success'=>false,'message'=>'Valor não é menor zero'],422);
        if ($number>=0 && !$abort) return false;
        return true;
    }
    public static function maiorQue(float $maxNumber,$minNumber, $abort = false):bool|array|\Illuminate\Http\JsonResponse
    {
        if ($maxNumber>=$minNumber && $abort) return response()->json(['success'=>false,'message'=>'Não é maior '.$minNumber],422);
        if ($maxNumber>=$minNumber && !$abort) return false;
        return true;
    }




}
