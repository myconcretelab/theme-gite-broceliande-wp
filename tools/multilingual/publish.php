<?php
$rows=json_decode(file_get_contents(__DIR__.'/content-20260906.json'),true,512,JSON_THROW_ON_ERROR);
$map=get_option('gb_multilingual_content',[]);
function gb_translation_topology($content) {
 $out=[];
 $walk=function($blocks)use(&$walk,&$out){foreach($blocks as $block){
  if(!$block['blockName'])continue;
  $out[]=['name'=>$block['blockName'],'data'=>array_intersect_key($block['attrs'],array_flip(['giteId','selectedGroupIds','selectedSectionIds','imageIds','ids','images']))];
  $walk($block['innerBlocks']);
 }};
 $walk(parse_blocks($content));return $out;
}
$publish=getenv('GB_PUBLISH_TRANSLATIONS')==='1';
foreach($rows as $row){
 if($row['type']==='wp_template_part')continue;
 $source=get_post($row['id']);
 if(!$source || $source->post_modified_gmt!==$row['source_modified'])throw new RuntimeException('Source changed: '.$row['id']);
 foreach(['en','es'] as $lang){
  $post=get_post($map[$row['id']][$lang]??0);
  if(!$post || gb_translation_topology($post->post_content)!==gb_translation_topology($source->post_content))throw new RuntimeException('Invalid translation structure: '.$row['id'].' '.$lang);
  if($post->post_type==='page' && pll_get_post_language($post->ID)!==$lang)throw new RuntimeException('Wrong language');
 }
}
foreach($rows as $row){
 if($row['type']==='wp_template_part')continue;
 foreach(['en','es'] as $lang){
  $id=$map[$row['id']][$lang];
  if($publish){$result=wp_update_post(['ID'=>$id,'post_status'=>'publish'],true);if(is_wp_error($result))throw new RuntimeException($result->get_error_message());}
  echo ($publish?'Published':'Verified')." $lang $id ".get_the_title($id)."\n";
 }
}
if($publish)flush_rewrite_rules();
