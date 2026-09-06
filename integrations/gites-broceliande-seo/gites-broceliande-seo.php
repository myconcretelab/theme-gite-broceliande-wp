<?php
/**
 * Plugin Name: Gîtes de Brocéliande — Référencement
 * Description: Titres locaux, métadonnées, données structurées et contenu Booked accessible sans JavaScript. Données issues de l’application de gestion.
 * Version: 1.0.8
 */
defined('ABSPATH') || exit;

function gbseo_language($id = 0) {
    $language = function_exists('pll_get_post_language') && $id ? pll_get_post_language($id) : (function_exists('pll_current_language') ? pll_current_language('slug') : 'fr');
    return in_array($language, ['fr','en','es'], true) ? $language : 'fr';
}
function gbseo_t($message, $values = [], $language = null) {
    static $dictionary;
    if ($dictionary === null) $dictionary = json_decode(file_get_contents(__DIR__.'/translations.json'), true) ?: [];
    $language = $language ?: gbseo_language();
    $text = $dictionary[$message][$language] ?? $message;
    foreach($values as $key => $value) $text = str_replace('{'.$key.'}', (string)$value, $text);
    return $text;
}
function gbseo_original_id($id) { return function_exists('pll_get_post') ? (pll_get_post($id, 'fr') ?: $id) : $id; }
function gbseo_data($id = 0) {
    static $cache = [];
    $id = $id ?: get_queried_object_id();
    if (isset($cache[$id])) return $cache[$id];
    $gite = get_post_meta($id, '_booked_default_gite_id', true);
    if (!$gite || !class_exists('Booked_Variables')) return $cache[$id] = [];
    $data = (new Booked_Variables(new Booked_ApiClient()))->get_gite_content($gite, false, gbseo_language($id));
    return $cache[$id] = is_wp_error($data) ? [] : $data;
}
function gbseo_pages() {
    return get_posts(['post_type'=>'page', 'post_status'=>'publish', 'numberposts'=>-1, 'suppress_filters'=>false, 'lang'=>gbseo_language(), 'meta_query'=>[['key'=>'_booked_default_gite_id','value'=>'','compare'=>'!=']], 'orderby'=>'menu_order title', 'order'=>'ASC']);
}
function gbseo_city($data) {
    $address = $data['adresse_complete'] ?? '';
    return preg_match('/\b\d{5}\s+(.+)$/u', $address, $match) ? trim($match[1]) : 'Brocéliande';
}
function gbseo_near_trehorenteuc($data) { return stripos(remove_accents(gbseo_city($data)), 'neant') !== false; }
function gbseo_capacity($data) { return absint($data['public_web_info']['max_people'] ?? 0); }
function gbseo_name($id, $data) { return $data['public_title'] ?? get_the_title($id); }
function gbseo_title($id) {
    if ($id === (int)get_option('page_on_front')) return gbseo_t('Gîtes en Brocéliande, près de Tréhorenteuc', [], gbseo_language($id));
    $d = gbseo_data($id);
    if (!$d) return get_the_title($id).' | Gîtes de Brocéliande';
    $n = gbseo_capacity($d);
    return gbseo_near_trehorenteuc($d) ? gbseo_t('{name} : gîte en Brocéliande, près de Tréhorenteuc', ['name'=>get_the_title($id)], gbseo_language($id)) : gbseo_t('{name} : gîte {capacity} à {city} en Brocéliande', ['name'=>get_the_title($id), 'capacity'=>$n?gbseo_t('{count} personnes',['count'=>$n],gbseo_language($id)):'', 'city'=>gbseo_city($d)], gbseo_language($id));
}
function gbseo_description($id) {
    $custom = get_post_meta($id, '_gbseo_description', true);
    if ($custom) {
        $d=gbseo_data($id);
        return str_replace(['{capacite}','{ville}'], [gbseo_capacity($d),gbseo_city($d)], $custom);
    }
    $d=gbseo_data($id);
    $s=$d['public_description'] ?? get_post_field('post_excerpt',$id);
    $s=trim(preg_replace('/\s+/u',' ',wp_strip_all_tags($s)));
    return mb_strlen($s)>160 ? mb_substr($s,0,157).'…' : $s;
}
function gbseo_image($id) {
    $gite=get_post_meta($id,'_booked_default_gite_id',true);
    $ids=$gite ? get_posts(['post_type'=>'attachment','post_status'=>'inherit','numberposts'=>1,'fields'=>'ids','meta_query'=>[['key'=>'_booked_gite_id','value'=>$gite],['key'=>'_booked_photo_is_public','value'=>'1']], 'meta_key'=>'_booked_photo_order','orderby'=>'meta_value_num','order'=>'ASC']) : [];
    $image_id=$ids[0] ?? get_post_thumbnail_id($id);
    if (!$image_id && $id==get_option('page_on_front')) $image_id=115;
    return $image_id ? wp_get_attachment_image_url($image_id,'large') : '';
}
function gbseo_other_seo() { return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION'); }
add_filter('pre_get_document_title', function($title) {
    return !gbseo_other_seo() && is_page() ? gbseo_title(get_queried_object_id()) : $title;
}, 30);
add_action('wp_head', function() {
    if (!is_page() || gbseo_other_seo()) return;
    $id=get_queried_object_id(); $url=get_permalink($id); $title=gbseo_title($id); $description=gbseo_description($id); $image=gbseo_image($id);
    $tags=['description'=>$description,'og:type'=>'website','og:locale'=>['fr'=>'fr_FR','en'=>'en_GB','es'=>'es_ES'][gbseo_language($id)],'og:site_name'=>'Gîtes de Brocéliande','og:title'=>$title,'og:description'=>$description,'og:url'=>$url,'twitter:card'=>$image?'summary_large_image':'summary','twitter:title'=>$title,'twitter:description'=>$description];
    if ($image) { $tags['og:image']=$image; $tags['twitter:image']=$image; $tags['og:image:alt']=get_the_title($id); }
    foreach($tags as $key=>$value) if ($value!=='') echo '<meta '.(str_starts_with($key,'og:')?'property':'name').'="'.esc_attr($key).'" content="'.esc_attr($value).'" />'."\n";
    $home=home_url('/');
    $graph=[['@type'=>'WebSite','@id'=>$home.'#website','url'=>$home,'name'=>'Gîtes de Brocéliande','inLanguage'=>['fr'=>'fr-FR','en'=>'en-GB','es'=>'es-ES'][gbseo_language($id)]], ['@type'=>is_front_page()?'CollectionPage':'WebPage','@id'=>$url.'#webpage','url'=>$url,'name'=>$title,'description'=>$description,'inLanguage'=>['fr'=>'fr-FR','en'=>'en-GB','es'=>'es-ES'][gbseo_language($id)],'isPartOf'=>['@id'=>$home.'#website']]];
    if (!is_front_page()) $graph[]=['@type'=>'BreadcrumbList','@id'=>$url.'#breadcrumb','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>gbseo_t('Gîtes en Brocéliande'),'item'=>$home],['@type'=>'ListItem','position'=>2,'name'=>get_the_title($id),'item'=>$url]]];
    $d=gbseo_data($id);
    if ($d) {
        $business=['@type'=>'LodgingBusiness','@id'=>$url.'#gite','name'=>gbseo_name($id,$d),'url'=>$url,'description'=>$description,'address'=>['@type'=>'PostalAddress','streetAddress'=>preg_replace('/,?\s*\b\d{5}\s+.*$/u','',$d['adresse_complete']??''),'addressLocality'=>gbseo_city($d),'addressCountry'=>'FR']];
        if(preg_match('/\b(\d{5})\b/',$d['adresse_complete']??'', $m)) $business['address']['postalCode']=$m[1];
        if($image) $business['image']=$image;
        $graph[]=$business; $graph[1]['mainEntity']=['@id'=>$url.'#gite'];
    } elseif(is_front_page()) {
        $items=[];
        foreach(gbseo_pages() as $i=>$p) $items[]=['@type'=>'ListItem','position'=>$i+1,'name'=>get_the_title($p),'url'=>get_permalink($p)];
        $graph[]=['@type'=>'ItemList','@id'=>$home.'#gites','itemListElement'=>$items];
        $graph[1]['mainEntity']=['@id'=>$home.'#gites'];
    }
    echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT).'</script>'."\n";
}, 5);
add_filter('wp_robots', function($robots) {
    if(is_archive() || is_search() || is_attachment()) { $robots['noindex']=true; unset($robots['index']); }
    return $robots;
});
add_filter('wp_sitemaps_add_provider', function($provider,$name) { return in_array($name,['users','taxonomies'],true)?false:$provider; },10,2);

// Insert actual, visible source content into the existing Booked container. The
// original widget replaces it with its interactive layout when JS is available.
add_filter('render_block_booked/gite-info', function($html,$block) {
    if(is_admin()) return $html;
    $a=$block['attrs']??[];
    $type=WP_Block_Type_Registry::get_instance()->get_registered('booked/gite-info');
    if($type) $a=$type->prepare_attributes_for_render($a);
    $g=!empty($a['giteId'])?$a['giteId']:get_post_meta(get_queried_object_id(),'_booked_default_gite_id',true);
    if(!$g || !class_exists('Booked_Variables')) return $html;
    $d=(new Booked_Variables(new Booked_ApiClient()))->get_gite_content($g);
    if(is_wp_error($d)) return $html;
    $sections=$a['selectedSectionIds']??[]; $groups=$a['selectedGroupIds']??[];
    $body='';
    $general=$a['selectedGeneralInfoItemIds']??['__booked_no_selection__'];
    $rows=['price-low'=>['Prix basse saison','prix_nuit_basse_saison'],'price-high'=>['Prix haute saison','prix_nuit_haute_saison'],'address'=>['Adresse','adresse_complete'],'arrival'=>['Arrivée','horaire_arrivee'],'departure'=>['Départ','horaire_depart']];
    if(!in_array('__booked_no_selection__',$general,true)) {
        $list='';
        foreach($rows as $key=>$row) if(!$general || in_array($key,$general,true)) {
            $val=$d['variables'][$row[1]]??$d[$row[1]]??'';
            if($val!=='') $list.='<li class="booked-gite-info__item"><span class="booked-gite-info__item-label">'.esc_html(gbseo_t($row[0])).'</span> <span class="booked-gite-info__item-value">'.esc_html($val).'</span></li>';
        }
        if($list) $body.='<ul class="booked-gite-info__items">'.$list.'</ul>';
    }
    if(!in_array('__booked_no_selection__',$sections,true)) foreach(($d['sections']??[]) as $section) {
        if($sections && !in_array($section['id'],$sections,true)) continue;
        $content='';
        foreach(($section['groupes']??[]) as $group) {
            if($groups && !in_array($group['id'],$groups,true)) continue;
            $content.='<article class="booked-gite-info__group">';
            if(!isset($a['showGroupTitles']) || $a['showGroupTitles']) $content.='<h3 class="booked-gite-info__group-title">'.esc_html($group['titre']??'').'</h3>';
            $content.='<div class="booked-gite-info__group-content"><ul class="booked-gite-info__items">';
            foreach(($group['items']??[]) as $item) {
                if(is_string($item)) $label=$item;
                elseif(($item['kind']??'')==='bed') {
                    $beds=['single'=>'Lit 90 (90 x 190 cm)','double'=>'Lit 140 (140 x 190 cm)','queen'=>'Lit 160 (160 x 200 cm)','king'=>'Lit 180 (180 x 200 cm)','bunk'=>'Lits superposés (90 x 190 cm)','sofa_bed'=>'Canapé-lit (140 x 190 cm)','baby'=>'Lit bébé (60 x 120 cm)'];
                    $count=max(1,(int)($item['count']??1));
                    $label=($count>1?$count.' x ':'').gbseo_t($beds[$item['type']??'']??'Lit');
                } else continue;
                $content.='<li class="booked-gite-info__item"><span class="booked-gite-info__item-label">'.esc_html($label).'</span></li>';
            }
            $content.='</ul>';
            if(!empty($a['showNotes']) && !empty($group['note'])) $content.='<p class="booked-gite-info__note">'.esc_html($group['note']).'</p>';
            $content.='</div></article>';
        }
        if($content) $body.='<section class="booked-gite-info__section">'.(!empty($a['showSectionTitles'])?'<h2 class="booked-gite-info__section-title">'.esc_html($section['titre']).'</h2>':'').$content.'</section>';
    }
    if(!empty($a['showTitle'])) $body='<h2 class="booked-gite-info__title">'.esc_html($d['public_title']??$d['nom']??'').'</h2>'.$body;
    if(!empty($d['public_technical_description'])) $body='<p>'.esc_html($d['public_technical_description']).'</p>'.$body;
    return $body && str_ends_with($html,'></div>') ? substr($html,0,-6).'<div class="booked-gite-info__layout booked-gite-info__layout--list">'.$body.'</div></div>' : $html;
}, 20, 2);

// Keep the primary title outside the JS widget so it is stable after hydration.
add_filter('render_block_booked/gite-cards',function($html,$block) {
    if(is_admin() || ($block['attrs']['layout']??'')!=='page-compact' || !is_page()) return $html;
    return '<nav class="gbseo-breadcrumb" aria-label="'.esc_attr(gbseo_t('Fil d’Ariane')).'"><a href="'.esc_url(home_url('/')).'">'.esc_html(gbseo_t('Gîtes en Brocéliande')).'</a> <span aria-hidden="true">›</span> '.esc_html(get_the_title()).'</nav><h1 class="gbseo-title">'.esc_html(gbseo_title(get_queried_object_id())).'</h1>'.$html;
},20,2);
add_shortcode('gbseo_gites',function() {
    $copy=[
        332=>['À deux','Un petit refuge, rien que pour vous','Un studio de charme avec un coin nuit en mezzanine, un jardin et le calme de la campagne. Une douce escapade à deux, à moins de 2 minutes en voiture de Tréhorenteuc.'],
        103=>['En famille','Les vacances, tout simplement','Vieilles pierres, poêle à bois et terrasses : un cocon de 60 m² pour partager des vacances en famille, à moins de 2 minutes en voiture de Tréhorenteuc.'],
        123=>['Au vert','Une maison au rythme du jardin','Une maison en pierre, un jardin arboré et les commerces accessibles à pied. Retrouvez Tréhorenteuc à moins de 2 minutes en voiture, puis le plaisir de rentrer chez vous.'],
        289=>['En groupe','Les grandes tablées et les retrouvailles','À Mauron, six chambres, un billard et une cour intérieure pour réunir famille et amis. Votre grande maison pour découvrir Brocéliande et le village de Tréhorenteuc.']
    ];
    $pages=gbseo_pages(); $order=[332,103,123,289];
    usort($pages,fn($a,$b)=>(array_search(gbseo_original_id($a->ID),$order)===false?99:array_search(gbseo_original_id($a->ID),$order))<=>(array_search(gbseo_original_id($b->ID),$order)===false?99:array_search(gbseo_original_id($b->ID),$order)));
    $s='<div class="gbseo-stays">';
    foreach($pages as $index=>$p) {
        $d=gbseo_data($p->ID); $n=gbseo_capacity($d); $url=get_permalink($p);
        $c=$copy[gbseo_original_id($p->ID)]??['Brocéliande',get_the_title($p),gbseo_description($p->ID)];
        $c=array_map('gbseo_t',$c);
        $s.='<article class="gbseo-stay"><a class="gbseo-stay-photo" href="'.esc_url($url).'" aria-label="'.esc_attr(gbseo_t('Découvrir')).' '.esc_attr(get_the_title($p)).'">';
        $gite=get_post_meta($p->ID,'_booked_default_gite_id',true);
        $photos=class_exists('Booked_PhotoSync')?(new Booked_PhotoSync(new Booked_ApiClient()))->get_public_photos($gite):[];
        if($photos) $s.=wp_get_attachment_image($photos[0]['id'],'large',false,['class'=>'gbseo-stay-image','alt'=>get_the_title($p).' — '.($photos[0]['alt']??'Gîte de charme en Brocéliande'),'loading'=>'lazy','decoding'=>'async','sizes'=>'(max-width: 700px) calc(100vw - 40px), (max-width: 1200px) calc((100vw - 100px) / 2), 550px']);
        $s.='<span class="gbseo-stay-badge">'.esc_html($c[0]).'</span></a><div class="gbseo-stay-body"><p class="gbseo-stay-kicker">'.sprintf('%02d',$index+1).' <span aria-hidden="true">—</span> '.esc_html($c[1]).'</p><h3><a href="'.esc_url($url).'">'.esc_html(get_the_title($p)).'</a></h3><ul class="gbseo-stay-facts" aria-label="'.esc_attr(gbseo_t('Caractéristiques')).'">';
        if($n)$s.='<li>'.esc_html(gbseo_t('Jusqu’à {count} personnes',['count'=>$n])).'</li>';
        if(!empty($d['public_web_info']['surface_m2']))$s.='<li>'.absint($d['public_web_info']['surface_m2']).' m²</li>';
        $s.='</ul><p class="gbseo-stay-description">'.esc_html($c[2]).'</p><a class="gbseo-stay-cta" href="'.esc_url($url).'">'.esc_html(gbseo_t('Découvrir')).' '.esc_html(get_the_title($p)).'<span aria-hidden="true">↗</span></a></div></article>';
    }
    return $s.'</div>';
});
add_filter('render_block_core/post-content',function($html) {
    if(!is_page() || !gbseo_data()) return $html;
    $s='<section class="gbseo-related"><h2>'.esc_html(gbseo_t('Brocéliande et Tréhorenteuc : nos autres gîtes')).'</h2><ul>';
    foreach(gbseo_pages() as $p) if($p->ID!==get_queried_object_id()) {
        $d=gbseo_data($p->ID); $n=gbseo_capacity($d);
        $s.='<li><a href="'.esc_url(get_permalink($p)).'">'.esc_html(get_the_title($p).($n?' — '.gbseo_t('{count} personnes',['count'=>$n]):'').' '.(gbseo_near_trehorenteuc($d)?gbseo_t('près de Tréhorenteuc'):gbseo_t('à {city}',['city'=>gbseo_city($d)]))).'</a></li>';
    }
    return $html.$s.'</ul></section>';
});
add_action('wp_enqueue_scripts',function() {
    wp_enqueue_style('gbseo',plugins_url('seo.css',__FILE__),[],'1.0.7');
});
// A small editor field keeps local search descriptions editable in WordPress.
add_action('add_meta_boxes_page',function(){add_meta_box('gbseo-description','Référencement — description pour les moteurs',function($post){
    wp_nonce_field('gbseo_save','gbseo_nonce');
    echo '<p>La capacité et la commune sont actualisées depuis Booked avec {capacite} et {ville}.</p><textarea name="gbseo_description" rows="4" style="width:100%">'.esc_textarea(get_post_meta($post->ID,'_gbseo_description',true)).'</textarea>';
},'page','normal');});
add_action('save_post_page',function($id){
    if(!isset($_POST['gbseo_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gbseo_nonce'])),'gbseo_save') || !current_user_can('edit_post',$id) || wp_is_post_revision($id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) return;
    if(isset($_POST['gbseo_description'])) update_post_meta($id,'_gbseo_description',sanitize_textarea_field(wp_unslash($_POST['gbseo_description'])));
});
// Reflect actual public-content changes from Booked in the native sitemap.
add_filter('wp_sitemaps_posts_entry', function($entry,$post) {
    if($post->post_type!=='page') return $entry;
    $d=gbseo_data($post->ID);
    if($d) {
        $public=['title'=>gbseo_title($post->ID),'description'=>gbseo_description($post->ID),'content'=>$d['public_description']??'', 'info'=>$d['public_web_info']??[], 'address'=>$d['adresse_complete']??'', 'sections'=>$d['sections']??[], 'photos'=>$d['photos']??[]];
        $hash=hash('sha256',wp_json_encode($public));
        if(get_post_meta($post->ID,'_gbseo_source_hash',true)!==$hash) {
            update_post_meta($post->ID,'_gbseo_source_hash',$hash);
            update_post_meta($post->ID,'_gbseo_source_modified',gmdate('c'));
        }
        $source=get_post_meta($post->ID,'_gbseo_source_modified',true);
        if($source && strtotime($source)>strtotime($entry['lastmod']??'1970-01-01')) $entry['lastmod']=$source;
    }
    return $entry;
},10,2);
