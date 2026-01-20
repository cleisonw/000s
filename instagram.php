<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);

if(!isset($_GET["user"])){
    echo json_encode(["erro"=>"Usuário não informado"]);
    exit;
}

$user = preg_replace("/[^a-zA-Z0-9._]/","",$_GET["user"]);

$url = "https://www.instagram.com/$user/?__a=1&__d=dis";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: Mozilla/5.0"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if(!$data || !isset($data["graphql"]["user"])){
    echo json_encode(["erro"=>"Perfil não encontrado"]);
    exit;
}

$u = $data["graphql"]["user"];

echo json_encode([
    "nome"=>$u["full_name"],
    "username"=>$u["username"],
    "bio"=>$u["biography"],
    "seguidores"=>$u["edge_followed_by"]["count"],
    "seguindo"=>$u["edge_follow"]["count"],
    "posts"=>$u["edge_owner_to_timeline_media"]["count"],
    "foto"=>$u["profile_pic_url_hd"]
], JSON_UNESCAPED_UNICODE);