<?php
/** Polylang integration for the block theme. Page translations stay native WordPress posts. */
defined('ABSPATH') || exit;

function gb_site_language(): string {
    $language = function_exists('pll_current_language') ? pll_current_language('slug') : 'fr';
    return in_array($language, ['fr','en','es'], true) ? $language : 'fr';
}

function gb_language_switcher(): string {
    if (!function_exists('pll_the_languages')) return '';
    $languages = pll_the_languages(['raw'=>1,'hide_if_empty'=>0,'hide_if_no_translation'=>1]);
    if (!$languages) return '';
    $labels=['fr'=>'Changer de langue','en'=>'Choose language','es'=>'Elegir idioma'];
    $html='<nav class="gb-language-switcher" aria-label="'.esc_attr($labels[gb_site_language()]).'">';
    foreach($languages as $language) {
        if(!in_array($language['slug'],['fr','en','es'],true))continue;
        $html.='<a href="'.esc_url($language['url']).'" hreflang="'.esc_attr($language['slug']).'" lang="'.esc_attr($language['slug']).'" aria-label="'.esc_attr($language['name']).'"'.(!empty($language['current_lang'])?' aria-current="page"':'').'>'.esc_html(strtoupper($language['slug'])).'</a>';
    }
    return $html.'</nav>';
}
add_shortcode('gb_language_switcher','gb_language_switcher');
add_filter('render_block_core/group',function($html,$block){
    if(!is_admin() && in_array('gb-site-header__secondary-row',explode(' ',$block['attrs']['className']??''),true)) {
        $end=strrpos($html,'</div>');
        if($end!==false)$html=substr_replace($html,gb_language_switcher(),$end,0);
    }
    return $html;
},20,2);

function gb_localized_url(string $url): string {
    if (!function_exists('pll_get_post') || gb_site_language()==='fr' || $url==='' || $url[0]==='#') return $url;
    static $cache=[];
    $key=gb_site_language().':'.$url;
    if(isset($cache[$key]))return $cache[$key];
    $parts=wp_parse_url(html_entity_decode($url));
    if(!$parts || (!empty($parts['host']) && $parts['host']!==wp_parse_url(get_option('home'),PHP_URL_HOST)) || (isset($parts['scheme']) && !in_array($parts['scheme'],['http','https'],true)))return $url;
    $base=explode('#',$url)[0];
    if(str_starts_with($base,'/'))$base=rtrim(get_option('home'),'/').$base;
    $id=url_to_postid($base);
    if(!$id && ($parts['path']??'/')==='/')$id=(int)get_option('page_on_front');
    $translated=$id?pll_get_post($id,gb_site_language()):0;
    return $cache[$key]=$translated?get_permalink($translated).(isset($parts['fragment'])?'#'.$parts['fragment']:''):$url;
}
add_filter('render_block_data',function($block){
    if(is_admin())return $block;
    $language=gb_site_language();
    if(($block['blockName']??'')==='core/template-part' && ($block['attrs']['slug']??'')==='footer' && $language!=='fr')$block['attrs']['slug']='footer-'.$language;
    if(($block['blockName']??'')==='core/navigation'){
        $map=get_option('gb_multilingual_content',[]);
        $reference=$block['attrs']['ref']??4;
        if(isset($map[$reference][$language]))$block['attrs']['ref']=$map[$reference][$language];
        $block['attrs']['ariaLabel']=['fr'=>'Menu principal','en'=>'Main menu','es'=>'Menú principal'][$language];
    }
    if(($block['blockName']??'')==='core/navigation-link' && function_exists('pll_get_post') && !empty($block['attrs']['id'])){
        $id=pll_get_post($block['attrs']['id'],$language);
        if($id){$block['attrs']['id']=$id;$block['attrs']['url']=get_permalink($id);}
    }
    return $block;
},20);
add_filter('render_block',function($html){
    if(is_admin() || gb_site_language()==='fr' || !str_contains($html,'href='))return $html;
    $processor=new WP_HTML_Tag_Processor($html);
    while($processor->next_tag('A')){
        if($processor->get_attribute('hreflang'))continue;
        $href=$processor->get_attribute('href');
        if(is_string($href))$processor->set_attribute('href',gb_localized_url($href));
    }
    return $processor->get_updated_html();
},50);
