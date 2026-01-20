<?php
header("Content-Type: application/json; charset=UTF-8");

$API_KEY = "e9358468d937bc8ab569576c7103adc568e2dccd";

if(!isset($_GET["user"])){
    echo json_encode(["erro"=>"Usuário não informado"]);
    exit;
}

$user = preg_replace("/[^a-zA-Z0-9._]/","",$_GET["user"]);
$url = "https://www.instagram.com/$user/";

$zen = "https://api.zenrows.com/v1/?apikey=$API_KEY&url=".urlencode($url)."&js_render=true&premium_proxy=true";

$html = file_get_contents($zen);

if(!$html){
    echo json_encode(["erro"=>"Falha ao acessar Instagram"]);
    exit;
}

// Captura JSON interno do Instagram
if(!preg_match('/"edge_followed_by":{"count":([0-9]+)/',$html,$seg)){
    echo json_encode(["erro"=>"Perfil não encontrado ou bloqueado"]);
    exit;
}

preg_match('/"edge_follow":{"count":([0-9]+)/',$html,$seg2);
preg_match('/"edge_owner_to_timeline_media":{"count":([0-9]+)/',$html,$posts);
preg_match('/"full_name":"(.*?)"/',$html,$nome);
preg_match('/"biography":"(.*?)"/',$html,$bio);
preg_match('/"profile_pic_url_hd":"(.*?)"/',$html,$foto);

echo json_encode([
  "nome"=>html_entity_decode(stripslashes($nome[1] ?? "")),
  "username"=>$user,
  "bio"=>html_entity_decode(stripslashes($bio[1] ?? "")),
  "seguidores"=>$seg[1] ?? 0,
  "seguindo"=>$seg2[1] ?? 0,
  "posts"=>$posts[1] ?? 0,
  "foto"=>stripslashes($foto[1] ?? "")
], JSON_UNESCAPED_UNICODE);
