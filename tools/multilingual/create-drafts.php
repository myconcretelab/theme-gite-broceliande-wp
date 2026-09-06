<?php
if (!function_exists('pll_save_post_translations')) throw new RuntimeException('Polylang required');
$rows = json_decode(file_get_contents(__DIR__.'/content-20260906.json'), true, 512, JSON_THROW_ON_ERROR);
$map = get_option('gb_multilingual_content', []);
foreach ($rows as $row) {
 $original=get_post($row['id']);
 if (!$original || $original->post_modified_gmt!==$row['source_modified']) throw new RuntimeException('Source changed: '.$row['id']);
 if ($row['type']==='wp_template_part') continue;
 foreach (['en','es'] as $lang) {
  if (!empty($map[$row['id']][$lang])) throw new RuntimeException('Translation already exists');
 }
}
foreach ($rows as $row) {
 if ($row['type']==='wp_template_part') continue;
 $original=get_post($row['id']);$map[$row['id']]['fr']=$original->ID;
 foreach (['en','es'] as $lang) {
  $text=$row['translations'][$lang];
  $slug=$row['id']===273?($lang==='en'?'home':'inicio'):$original->post_name.'-'.$lang;
  $id=wp_insert_post(wp_slash(['post_type'=>$original->post_type,'post_status'=>'draft','post_name'=>$slug,'post_title'=>$text['title'],'post_content'=>$text['content'],'menu_order'=>$original->menu_order,'post_author'=>$original->post_author]),true);
  if(is_wp_error($id))throw new RuntimeException($id->get_error_message());
  foreach(get_post_meta($original->ID) as $key=>$values) {
   if(in_array($key,['_edit_lock','_edit_last','_gbseo_description','_gbseo_source_hash','_gbseo_source_modified'],true))continue;
   foreach($values as $value)add_post_meta($id,$key,maybe_unserialize($value));
  }
  if($text['seo'])update_post_meta($id,'_gbseo_description',$text['seo']);
  update_post_meta($id,'_gb_translation_source',$original->ID);
  if($original->post_type==='page')pll_set_post_language($id,$lang);
  $map[$original->ID][$lang]=$id;
  update_option('gb_multilingual_content',$map);
  echo "$lang $id {$text['title']}\n";
 }
 if($original->post_type==='page')pll_save_post_translations($map[$original->ID]);
}
