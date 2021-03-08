<?php
//входные данные
$inputString="a<b:c<d::e::f>>g";
//разбиение строки на токены
$inputArray=str_split ($inputString);

//Проверка строки на правильность
function inputValidation ($array, $string){
    $counterParenthesis=0;
    foreach($array as $value){
        switch ($value){
            case "<":
                $counterParenthesis++;
                break;
            case ">":
                $counterParenthesis--;
                break;
            default:
                break;
        }
        if ($counterParenthesis<0) {
            throw new Exception('Error in input string. (check /< or />)');
        }
    }
    if ($counterParenthesis>0) {
        throw new Exception('Error in input string. (check quantities < or >)');
    }
    //дополнительная проверка входящей строки на корректность
    if(preg_match('/<::|<>|::>|::::/', $string) == 1){
        throw new Exception('Error in input string. (one of [<::, <>, ::>, :::] DETECTED)');
    }
}
try {
    inputValidation ($inputArray, $inputString);
} catch (Exception $e) {
    echo 'ERROR: ',  $e->getMessage(), "\n";
    exit;
}

//для упрощения разбора произведем предварительную замену символов :: на : 
echo ("<br>");
print_r ($inputArray);

foreach ($inputArray as $key=>&$value){
    if ($value == ":" && $inputArray[$key+1] == ":" && $inputArray[$key+2] == ":"){
        $inputArray[$key-1]=$inputArray[$key-1].":";
        array_splice($inputArray, $key+1, 2);
    } elseif ($value == ":" && $inputArray[$key+1] == ":" && $inputArray[$key+2] != ":") {
        array_splice($inputArray, $key+1, 1);
    }elseif ($value == ":" && $inputArray[$key+1] != ":" && $inputArray[$key-1] != ":") {
        $inputArray[$key-1]=$inputArray[$key-1].":";
        array_splice($inputArray, $key, 1);
    } else {
        continue;
    }
}
unset($value);
echo ("<br>");
print_r ($inputArray);

//разбиение строки на токены
$tokens=[];
$currentToken="";
foreach ($inputArray as $key=>$value){
    switch ($value){
        case "<":
        case ":":
        case ">":
            if($currentToken!=""){
                array_push($tokens, $currentToken);
            }
            array_push($tokens, $value);
            $currentToken="";
            break;
        default:
            $currentToken=$currentToken.$value;
            if ($key == count($inputArray)-1){
                array_push($tokens, $currentToken);
            }
            break;
    }
}
//Строка после разбиения на токены имеет вид:например $tokens=["<","a",":","b",":","с",":","d",">","e"];
//функция анализа верхушки стека
function stackProduce($array){
    $stackPointer=count($array)-1;
    switch ($array[$stackPointer]) {
        case "<":
        case ":":
            break;
        case ">":
            //функция произведения выражения < : >
            $array=orProd($array);
            $array[count($array)-2]=$array[count($array)-1];
            array_pop($array);
            break;
        default:
            if ($array[$stackPointer-1]!="<" &&
                $array[$stackPointer-1]!=":" &&
                $array[$stackPointer-1]!=">" &&
                $array[$stackPointer-1]!="stackBottom"
                ){
                    //функция объединения значений
                    $array[$stackPointer-1]=concat ($array[$stackPointer-1] , $array[$stackPointer]);
                    array_pop($array);
            }else{
            break;}
    }
return $array;
}

//функция объединения значений
function concat ($element1, $element2){
    switch (is_array($element1)){
        case true:{
            switch (is_array($element2)){
                case true://оба элемента массивы (11)
                    $resultArray=[];
                    foreach($element1 as $value1){
                        foreach($element2 as $value2){
                            array_push($resultArray, $value1." ".$value2);
                        }
                    }
                    return $resultArray;
                    break;

                case false://первый массив, второй не массив(10)
                    $resultArray=[];
                    foreach($element1 as $value){
                        array_push($resultArray, $value." ".$element2);
                    }
                    return $resultArray;
                    break;
            }
        }
        case false:{
            switch (is_array($element2)){
                case true:// (01)
                    $resultArray=[];
                    foreach($element2 as $value){
                        array_push($resultArray, $element1." ".$value);
                    }
                    return $resultArray;
                    break;

                case false: // (00)
                    return $element1." ".$element2;
                    break;
            }
        }
    }
}

//функция произведения выражения < : >
function orProd($array){
    array_pop($array);
    while ($array[count($array)-2]!="<") {
        $stackPointer=count($array)-1;
        switch ($array[$stackPointer-1]){
            case ":":
                $array[$stackPointer-2]=orProdСolon ($array[$stackPointer-2], $array[$stackPointer]);             
                array_pop($array);
                array_pop($array);

                break;
            default:
                $array[$stackPointer-1]=concat ($array[$stackPointer-1] , $array[$stackPointer]);
                array_pop($array);
                break;
        }
    }
    return $array;
}

////функция произведения выражения  ":" (подфункция функции orProd()) 
function orProdСolon($element1, /*:*/$element2){
    switch (is_array($element1)){
        case true:{
            switch (is_array($element2)){
                case true://оба элемента массивы (11)
                    $resultArray=$element1;
                    foreach($element2 as $value){
                        array_push($resultArray, $value);
                    }
                    return $resultArray;
                    break;

                case false://первый массив, второй не массив(10)
                    $resultArray=$element1;
                    array_push($resultArray, $element2);
                    return $resultArray;
                    break;
            }
        }
        case false:{
            switch (is_array($element2)){
                case true:// (01)
                    $resultArray=$element2;
                    array_unshift($resultArray, $element1);
                    return $resultArray;
                    break;

                case false: // (00)
                    return array($element1, $element2);
                    break;
            }
        }
    }    
}

//наполнение и работа со стеком
$stack=["stackBottom"];//указатель на дно стека
foreach ($tokens as $key=>$value){
    array_push($stack, $value);
    $stack=stackProduce($stack);
}
echo ("<br>-----tokens:--------------------<br>");
print_r($tokens);
echo ("<br>-----stack:--------------------<br>");
print_r($stack);


//проработанный стек нормализуется до двух элементов (дно стека и массив результатов)

echo ("<br>-----stack (нормализованный):--------------------<br>");
print_r($stack);
?>