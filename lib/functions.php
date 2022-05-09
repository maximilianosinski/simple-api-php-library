<?php
class Responses{
    public static function Return($Success, $Status, $Code, $Message, $Data, $InvalidParameter){
        return array
        (
            "success" => $Success,
            "status" => $Status,
            "code" => $Code,
            "message" => $Message,
            "data" => $Data,
            "invalidParameter" => $InvalidParameter
        );
    }
    public static function ReturnByResponse($Response){
        $Success = (bool)$Response["response"]["code"];
        $Status = $Response["response"]["status"];
        $Code = $Response["response"]["code"];
        $Message = $Response["response"]["message"];
        $Data = $Response["response"]["data"];
        $InvalidParameter = $Response["response"]["invalidParameter"];
        return array
        (
            "success" => $Success,
            "status" => $Status,
            "code" => $Code,
            "message" => $Message,
            "data" => $Data,
            "invalidParameter" => $InvalidParameter
        );
    }
    public static function ResponseByReturn($Return){
        $Status = $Return["status"];
        $Code = $Return["code"];
        $Message = $Return["message"];
        $Data = $Return["data"];
        $InvalidParameter = $Return["invalidParameter"];
        header("HTTP/1.1 $Status");
        echo json_encode(array
        (
            "response" => array
            (
                "status" => $Status,
                "code" => $Code,
                "message" => $Message,
                "data" => $Data,
                "invalidParameter" => $InvalidParameter
            )
        ), JSON_PRETTY_PRINT);
    }
    public static function Response($Status, $Code, $Message, $Data, $InvalidParameter){
        header("HTTP/1.1 $Status");
        echo json_encode(array
        (
            "response" => array
            (
                "status" => $Status,
                "code" => $Code,
                "message" => $Message,
                "data" => $Data,
                "invalidParameter" => $InvalidParameter
            )
        ), JSON_PRETTY_PRINT);
    }
    public static function ResponseByReturnTranslated($Return, $TargetLanguageCode){
        $Status = $Return["status"];
        $Code = $Return["code"];
        $Message = $Return["message"];
        $Translate = Typography::Translate($Message, $TargetLanguageCode);
        if($Translate["success"]){
            $Message = $Translate["data"];
        }
        $Data = $Return["data"];
        $InvalidParameter = $Return["invalidParameter"];
        header("HTTP/1.1 $Status");
        echo json_encode(array
        (
            "response" => array
            (
                "status" => $Status,
                "code" => $Code,
                "message" => $Message,
                "data" => $Data,
                "invalidParameter" => $InvalidParameter
            )
        ), JSON_PRETTY_PRINT);
    }
    public static function ResponseByReturnAutoTranslated($Return){
        $Status = $Return["status"];
        $Code = $Return["code"];
        $Message = $Return["message"];
        $AutoTranslate = Typography::AutoTranslate($Message);
        if($AutoTranslate["success"]){
            $Message = $AutoTranslate["data"];
        }
        $Data = $Return["data"];
        $InvalidParameter = $Return["invalidParameter"];
        header("HTTP/1.1 $Status");
        echo json_encode(array
        (
            "response" => array
            (
                "status" => $Status,
                "code" => $Code,
                "message" => $Message,
                "data" => $Data,
                "invalidParameter" => $InvalidParameter
            )
        ), JSON_PRETTY_PRINT);
    }
}
class Requests{
    public static function Get($URL, $Data){
        $context = stream_context_create(array(
            "http" => array("ignore_errors" => true),
        ));

        $Result = file_get_contents("$URL?$Data", false, $context);
        return Responses::Return(true, 200, 1, "Got response.", $Result, null);
    }
    public static function GetArray($URL, $Data){
        $context = stream_context_create(array(
            "http" => array("ignore_errors" => true),
        ));

        $Result = json_decode(file_get_contents("$URL?$Data", false, $context), true);
        if($Result != null){
            return Responses::Return(true, 200, 1, "Got response.", $Result, null);
        } else {return Responses::Return(true, 200, 1, "Response gave back invalid JSON.", "failed:response:json:invalid", "response-json");}
    }
    public static function Post($URL, $Data){
        $Options = array(
            "http" => array(
                "header" => "Content-type: application/json\r\n",
                "method" => "POST",
                "content" => http_build_query($Data)
            )
        );
        $Context  = stream_context_create($Options);
        $Result = file_get_contents($URL, false, $Context);
        if($Result != null){
            return Responses::Return(true, 200, 1, "Got response.", $Result, null);
        } else {return Responses::Return(false, 500, 1, "Unknown error.", $Result, null);}
    }
    public static function Request($URL, $Method, $Data){
        $Options = array(
            "http" => array(
                "header" => "Content-type: application/json\r\n",
                "method" => $Method,
                "content" => http_build_query($Data)
            )
        );
        $Context  = stream_context_create($Options);
        $Result = file_get_contents($URL, false, $Context);
        if($Result != null){
            return Responses::Return(true, 200, 1, "Got response.", $Result, null);
        } else {return Responses::Return(false, 500, 1, "Unknown error.", $Result, null);}
    }
    public static function URLExists($URL){
        $FileHeaders = @get_headers($URL);
        if(!$FileHeaders || $FileHeaders[0] == "HTTP/1.1 404 Not Found") {
            return Responses::Return(false, 404, 0, "URL doesn't exist.", "failed:url:doesnt:exist", "url");
        }
        else {
            return Responses::Return(true, 200, 1, "URL exists.", "success:url:exists", null);
        }
    }
}
class FileSystem{
    public static function ContentExists($File){
        if(file_exists($File)){
            return Responses::Return(true, 200, 1, "Content exists.", "success:content:exists", null);
        } else {return Responses::Return(false, 404, 0, "Content doesn't exist.", "failed:content:doesnt:exist", "file");}
    }
    public static function WriteContent($File, $Content){
        if(file_put_contents($File, $Content)){
            return Responses::Return(true, 200, 1, "Wrote content successfuly.", "success:write:content", null);
        } else {return Responses::Return(false, 500, 0, "Unknown error.", "failed:error:unknown", null);}
    }
    public static function WriteArray($File, $Array){
        if(file_put_contents($File, json_encode($Array, JSON_PRETTY_PRINT))){
            return Responses::Return(true, 200, 1, "Wrote array successfuly.", "success:write:array", null);
        } else {return Responses::Return(false, 500, 0, "Unknown error.", "failed:error:unknown", null);}
    }
    public static function GetContent($File){
        $ContentExists = self::ContentExists($File);
        if($ContentExists["success"]){
            $Content = file_get_contents($File);
            return Responses::Return(true, 200, 1, "Got content.", $Content, null);
        } else {return $ContentExists;}
    }
    public static function GetArray($File){
        $ContentExists = self::ContentExists($File);
        if($ContentExists["success"]){
            $Content = json_decode(file_get_contents($File), true);
            if($Content != null){
                return Responses::Return(true, 200, 1, "Got array.", $Content, null);
            } else {return Responses::Return(false, 429, 0, "File contains invalid JSON Data.", "failed:file:json:data:invalid", null);}
        } else {return $ContentExists;}
    }
    public static function DeleteContent($File){
        $ContentExists = self::ContentExists($File);
        if($ContentExists["success"]){
            unlink($File);
            return Responses::Return(true, 200, 1, "Deleted content.", "success:content:deleted", null);
        } else {return $ContentExists;}
    }
}
class Typography{
    public static function Translate($Text, $TargetLanguageCode){
        $Request = Requests::GetArray("https://api.maxeman.com/v1/translate", "text=".urlencode($Text)."&source_lang=auto&target_lang=$TargetLanguageCode");
        if($Request["success"]){
            $Response = $Request["data"];
            if($Response["response"]["code"] == 1){
                $TranslatedText = $Response["response"]["data"];
                return Responses::Return(true, 200, 1, "Translated text.", $TranslatedText, null);
            } else {return Responses::ReturnByResponse($Response);}
        } else {return $Request;}
    }
    public static function AutoTranslate($Text){
        $IPGeoLocation = User::IPGeoLocation();
        if($IPGeoLocation[0]){
            $CountryCode = $IPGeoLocation["countryCode"];
            $Translate = self::Translate($Text, $CountryCode);
            if($Translate["success"]){
                return $Translate;
            } else {return $Translate;}
        } else {return Responses::Return(false, 500, 0, "Unknown error.", "failed:error:unknown", null);}
    }
}
class User{
    public static function IP(){
        return $_SERVER["REMOTE_ADDR"];
    }
    public static function UserAgent(){
        return $_SERVER["HTTP_USER_AGENT"];
    }
    public static function IPGeoLocation(){
        $Request = Requests::GetArray("https://ipapi.co/".self::IP()."/json/", null);
        if($Request["success"]){
            $Response = $Request["data"];
            if(array_key_exists("city", $Response)){
                $Country = $Response["country_name"];
                $CountryCode = strtolower($Response["country_code"]);
                $Region = $Response["region"];
                $City = $Response["city"];
                return array
                (
                    true,
                    "country" => $Country,
                    "countryCode" => $CountryCode,
                    "region" => $Region,
                    "city" => $City
                );
            } else {return array(false);}
        } else {return array(false);}
    }
}