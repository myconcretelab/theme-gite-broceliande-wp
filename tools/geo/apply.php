<?php
/** Apply the reviewed French editorial update once, or restore its saved snapshot. */
if (!defined('WP_CLI') || !WP_CLI) { exit; }
$mode=$args[0]??'check';
if(!in_array($mode,['check','apply','rollback'],true)) WP_CLI::error('Use check, apply or rollback.');
$pages=json_decode(file_get_contents(__DIR__.'/pages.json'),true,512,JSON_THROW_ON_ERROR);
$key='gbseo_editorial_20260913_backup';
$backup=get_option($key);
function gbgeo_media_blocks($blocks) {
    $result=[];
    foreach($blocks as $block) {
        if(str_starts_with($block['blockName']??'','booked/') || in_array($block['blockName'],['core/image','core/cover'],true)) $result[]=[$block['blockName'],$block['attrs']];
        $result=array_merge($result,gbgeo_media_blocks($block['innerBlocks']));
    }
    return $result;
}
if($mode==='rollback') {
    if(!$backup) WP_CLI::error('No backup found.');
    foreach($pages as $id=>$row) {
        if(get_post_field('post_content',$id)!==$row['content'] || get_post_meta($id,'_gbseo_title',true)!==$row['title'] || get_post_meta($id,'_gbseo_description',true)!==$row['description']) WP_CLI::error('Later edit detected; restore manually for page '.$id);
    }
    foreach($backup as $id=>$row) {
        $result=wp_update_post(wp_slash(['ID'=>$id,'post_content'=>$row['content']]),true);
        if(is_wp_error($result)) WP_CLI::error($result->get_error_message());
        foreach(['_gbseo_title'=>'title','_gbseo_description'=>'description'] as $meta=>$field) {
            if($row[$field.'_exists']) update_post_meta($id,$meta,wp_slash($row[$field]));
            else delete_post_meta($id,$meta);
        }
    }
    WP_CLI::success('Original content restored; backup retained.');
    return;
}
$before=[]; $already=0;
foreach($pages as $id=>$row) {
    $post=get_post($id);
    if(!$post || $post->post_status!=='publish' || (function_exists('pll_get_post_language') && pll_get_post_language($id)!=='fr')) WP_CLI::error('Unexpected target '.$id);
    $current=$post->post_content;
    if($current===$row['content'] && get_post_meta($id,'_gbseo_title',true)===$row['title'] && get_post_meta($id,'_gbseo_description',true)===$row['description']) { $already++; continue; }
    if(hash('sha256',$current)!==$row['before_hash'] || get_post_meta($id,'_gbseo_description',true)!==$row['before_description'] || get_post_meta($id,'_gbseo_title',true)!=='') WP_CLI::error('Source changed for page '.$id.'; re-review before applying.');
    if(gbgeo_media_blocks(parse_blocks($current))!==gbgeo_media_blocks(parse_blocks($row['content']))) WP_CLI::error('Media or Booked blocks changed for '.$id);
    $before[$id]=['content'=>$current,'title'=>get_post_meta($id,'_gbseo_title',true),'description'=>get_post_meta($id,'_gbseo_description',true),'title_exists'=>metadata_exists('post',$id,'_gbseo_title'),'description_exists'=>metadata_exists('post',$id,'_gbseo_description')];
    WP_CLI::log('Validated French page '.$id.': media and booking blocks preserved.');
}
if($already===count($pages)) { WP_CLI::success('Update already applied.'); return; }
if($already || $backup) WP_CLI::error('Partial application or existing backup: inspect before retrying.');
if($mode==='check') { WP_CLI::success('All preconditions pass. No changes made.'); return; }
if(!add_option($key,$before,'',false)) WP_CLI::error('Could not save backup.');
foreach($pages as $id=>$row) {
    $result=wp_update_post(wp_slash(['ID'=>$id,'post_content'=>$row['content']]),true);
    if(is_wp_error($result)) WP_CLI::error($result->get_error_message());
    update_post_meta($id,'_gbseo_title',wp_slash($row['title']));
    update_post_meta($id,'_gbseo_description',wp_slash($row['description']));
    if(get_post_field('post_content',$id)!==$row['content']) WP_CLI::error('Content did not persist for '.$id.'; backup retained.');
}
WP_CLI::success('Five French pages updated. Original content saved in '.$key.'.');
