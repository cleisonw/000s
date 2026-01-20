<?php
header("Content-Type: application/json");

$user = $_GET["user"];

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

if(!$data){
    echo json_encode(["erro" => "Usuário não encontrado"]);
    exit;
}

$userData = $data["graphql"]["user"];

echo json_encode([
    "nome" => $userData["full_name"],
    "username" => $userData["username"],
    "bio" => $userData["biography"],
    "seguidores" => $userData["edge_followed_by"]["count"],
    "seguindo" => $userData["edge_follow"]["count"],
    "posts" => $userData["edge_owner_to_timeline_media"]["count"],
    "foto" => $userData["profile_pic_url_hd"]
]);