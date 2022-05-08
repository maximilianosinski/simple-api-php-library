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
                return Responses::Return(true, 200, 1, "Got content.", $Content, null);
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