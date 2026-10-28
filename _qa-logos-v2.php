<?php
if (($_GET['k']??'')!=='tsme-7f3a91') { http_response_code(404); exit; }
require __DIR__.'/wp-load.php';
header('Content-Type: text/plain');
// post title => [new relative file, w, h]
$map = ['ifs'=>['2026/10/ifs-capital-logo.png',756,180,'IFS Capital'], 'maybank'=>['2026/10/maybank-logo.png',831,180,'Maybank']];
$up = wp_upload_dir();
foreach ($map as $title=>$m) {
  $p = get_posts(['post_type'=>'client_logo','title'=>$title,'posts_per_page'=>1,'post_status'=>'any']);
  if (!$p) { echo "no post $title\n"; continue; }
  $p = $p[0]; $aid = get_post_thumbnail_id($p->ID);
  $abs = $up['basedir'].'/'.$m[0];
  if (!file_exists($abs)) { echo "missing file $abs\n"; continue; }
  update_post_meta($aid,'_wp_attached_file',$m[0]);
  wp_update_attachment_metadata($aid,['width'=>$m[1],'height'=>$m[2],'file'=>$m[0],'sizes'=>[],'image_meta'=>[]]);
  global $wpdb; $wpdb->update($wpdb->posts,['guid'=>$up['baseurl'].'/'.$m[0],'post_mime_type'=>'image/png'],['ID'=>$aid]);
  update_post_meta($aid,'_wp_attachment_image_alt',$m[3]);
  update_post_meta($p->ID,'_thinksme_logo_tint',1);
  clean_post_cache($aid);
  $src = wp_get_attachment_image_src($aid,'full');
  echo "post {$p->ID} ($title) att $aid -> ".basename($src[0])." {$src[1]}x{$src[2]}\n";
}
