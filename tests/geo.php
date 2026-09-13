<?php
// Run with php tests/geo.php. Contract checks without network or database writes.
define('ABSPATH',__DIR__);
$hooks=[];$shortcodes=[];$current=289;$front=false;$meta=[];$query=[];$http_status=404;
function add_action($n,$f,$p=10,$args=1){global $hooks;$hooks[$n][$p][]=$f;}
function add_filter($n,$f,$p=10,$args=1){add_action($n,$f,$p,$args);}
function add_shortcode($n,$f){global $shortcodes;$shortcodes[$n]=$f;}
function get_queried_object_id(){global $current;return $current;}
function get_post_meta($id,$key,$single=true){global $meta;return $meta[$id][$key]??($key==='_booked_default_gite_id'?'gite':'');}
function get_option($k){return $k==='page_on_front'?273:null;}
function get_the_title($id=null){return ($id===332?'L&rsquo;Oncle Edmond':'Le Liberté');}
function get_permalink($id){return $id===273?'https://gites-broceliande.com/':'https://gites-broceliande.com/le-liberte/';}
function home_url($p){return 'https://gites-broceliande.com'.$p;}
function get_posts($args){return [];}
function get_post_thumbnail_id($id){return 0;}
function get_post_field($key,$id){return '';}
function is_front_page(){global $front;return $front;}
function is_page(){return true;}
function is_wp_error($v){return false;}
function absint($v){return abs((int)$v);}
function wp_strip_all_tags($v){return strip_tags($v);}
function remove_accents($v){return $v;}
function esc_attr($v){return htmlspecialchars((string)$v,ENT_QUOTES);}
function esc_html($v){return htmlspecialchars((string)$v,ENT_QUOTES);}
function esc_url($v){return esc_attr($v);}
function wp_json_encode($v,$flags=0){return json_encode($v,$flags);}
function gb_test($bool,$message){if(!$bool) throw new RuntimeException($message);}
class Booked_ApiClient {}
class Booked_Variables {
    function __construct($client){}
    function get_gite_content(...$args){return ['public_title'=>'Le Liberté','adresse_complete'=>'1 place de la Liberté, 56430 Mauron','public_description'=>'Une maison de charme.','public_web_info'=>['max_people'=>15,'sleeping_capacity'=>20,'surface_m2'=>240]];}
}
class TestProvider {
    function get_object_subtypes(){return ['page'=>[]];}
    function get_url_list($page,$subtype){return $page===1?[['loc'=>'https://gites-broceliande.com/']]:[];}
}
class TestRegistry {function get_provider($n){return $n==='posts'?new TestProvider():null;}}
class TestServer {public $registry;function __construct(){$this->registry=new TestRegistry();}function sitemaps_enabled(){global $enabled;return $enabled;}}
$enabled=true;
function wp_sitemaps_get_server(){return new TestServer();}
function get_query_var($k){global $query;return $query[$k]??'';}
function status_header($s){global $http_status;$http_status=$s;}
require __DIR__.'/../integrations/gites-broceliande-seo/gites-broceliande-seo.php';
$meta[289]['_gbseo_title']='Le Liberté : gîte {capacite} personnes à Mauron en Brocéliande';
gb_test(str_contains(gbseo_title(289),'15 personnes'),'Title must use allowed occupancy');
ob_start();$hooks['wp_head'][5][0]();$head=ob_get_clean();
preg_match('~<script type="application/ld\+json">(.*?)</script>~s',$head,$match);
$graph=json_decode($match[1],true,512,JSON_THROW_ON_ERROR)['@graph'];
$types=array_column($graph,'@type');
$stay=$graph[array_search('Accommodation',$types)];
$business=$graph[array_search('LodgingBusiness',$types)];
gb_test($stay['occupancy']['maxValue']===15,'Do not advertise 20 sleeping places as capacity');
gb_test($stay['floorSize']['value']===240 && $stay['floorSize']['unitCode']==='MTK','Surface must come from Booked');
gb_test(!isset($business['occupancy']) && $business['containsPlace']['@id']===$stay['@id'],'Accommodation properties on correct entity');
gb_test(count(array_unique(array_column($graph,'@id')))===count($graph),'No duplicate entity IDs');
gb_test(str_contains($shortcodes['gbseo_facts'](),'15') && str_contains($shortcodes['gbseo_facts'](),'240'),'Visible facts agree with schema');
$meta[289]['_gbseo_title']='Title </script> "special"';
ob_start();$hooks['wp_head'][5][0]();$escaped=ob_get_clean();
gb_test(substr_count($escaped,'</script>')===1,'Schema text must not end script context');
foreach([
 [['sitemap'=>'index'],true,200],
 [['sitemap'=>'posts','sitemap-subtype'=>'page','paged'=>1],true,200],
 [['sitemap'=>'posts','sitemap-subtype'=>'page','paged'=>2],true,404],
 [['sitemap'=>'posts','sitemap-subtype'=>'not-a-type','paged'=>1],true,404],
 [['sitemap'=>'unknown'],true,404],
 [['sitemap-stylesheet'=>'index'],true,200],
 [['sitemap-stylesheet'=>'unknown'],true,404],
 [['sitemap'=>'index'],false,404],
 [[],true,404],
] as [$query,$enabled,$expected]){$http_status=404;$hooks['template_redirect'][9][0]();gb_test($http_status===$expected,'Unexpected sitemap status '.json_encode($query));}
echo "PASS: allowed occupancy, schema entities, visible facts, escaping and valid/invalid sitemap routes.\n";
