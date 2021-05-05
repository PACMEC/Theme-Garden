<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Themes
 * @category   Garden
 * @version    1.0.1
 */
 global $PACMEC;
$limit         = 9;
$page          = isset($PACMEC['fullData']['page']) ? $PACMEC['fullData']['page'] : 1;
$order_by      = isset($PACMEC['fullData']['order_by']) ? $PACMEC['fullData']['order_by'] : "default";
$offset        = ($page-1)*$limit;
$limit_news    = 3;

$in_gallery_sql = "";
$options_order_by = [
 "default"    => "order_by_created_asc",
 "title_asc"  => "order_by_title_asc",
 "title_desc" => "order_by_title_desc",
];

switch ($order_by) {
 case 'price_asc':
   $orderby = " ORDER BY P.`title` ASC ";
   break;
 case 'price_desc':
   $orderby = " ORDER BY P.`title` DESC ";
   break;
 default:
   $orderby = " ORDER BY P.`created` ASC ";
   break;
}

$sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('posts')}` P
WHERE P.`status` IN ('publish') {$in_gallery_sql} {$orderby} LIMIT {$limit} OFFSET {$offset}";
$sql_total = $GLOBALS['PACMEC']['DB']->FetchObject("SELECT COUNT(P.`id`) as `total`
FROM `{$GLOBALS['PACMEC']['DB']->getTableName('posts')}` P
WHERE P.`status` IN ('publish') {$in_gallery_sql}
", []);

$items = [];
foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []) as $item) {
 $items[] = new \PACMEC\System\Posts((object) ["id"=>$item->id]);
}

$total_result = $sql_total->total;
$max_pages_float = (float) ($total_result/$limit);
$max_pages = (int) ($total_result/$limit);
if($max_pages<$max_pages_float) $max_pages += 1;
$_url_form = [];
if(isset($_url_form['page'])) $_url_form['page'] = $PACMEC['fullData']['page'];
if(isset($PACMEC['fullData']['filter_text'])) $PACMEC['fullData']['filter_text'] = $PACMEC['fullData']['filter_text'];
if(isset($PACMEC['fullData']['order_by']) && $PACMEC['fullData']['order_by'] !== 'default') $_url_form['order_by'] = $PACMEC['fullData']['order_by'];
$url_base_form = $PACMEC['path'].http_build_query(array_merge($_url_form, []));
$_url_pagination = $PACMEC['fullData'];
if(isset($_url_pagination['page'])) unset($_url_pagination['page']);
$url_pagination = $PACMEC['path'].http_build_query($_url_pagination);

?>
<div class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <?php foreach ($items as $item): ?>
          <article class="post-item">
              <div class="post-media">
                  <div class="post-type-carousel">
                      <img src="<?= $item->thumb; ?>" alt="<?= $item->slug; ?>" />
                  </div>
              </div>
              <div class="date-meta">27<span>May 2016</span></div>
              <div class="post-container">
                  <div class="post-header">
                      <h3 class="post-title">
                          <a href="<?= $item->link_href; ?>"><?= $item->title; ?></a>
                      </h3>
                      <!--//
                      <ul class="post-meta">
                          <li class="author-meta"><i class="fa fa-user" aria-hidden="true"></i>By <a
                                  href="#">John Doe</a></li>
                          <li class="comment-meta"><i class="fa fa-comment" aria-hidden="true"></i> <a
                                  href="#">3 Comments</a></li>
                          <li class="category-meta"><i class="fa fa-tag" aria-hidden="true"></i> <a
                                  href="#">Garden</a></li>
                      </ul>
                      --->
                  </div>

                  <div class="post-content">
                    <p><?= substr(strip_tags($item->content), 0, 254); ?></p>
                    <a class="btn btn-default" href="<?= $item->link_href; ?>"><?= __a('read_more'); ?></a>
                  </div>
              </div>
          </article>
        <?php endforeach; ?>


        <div class="pagination-container text-center">
          <ul class="pagination">
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                    <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                </a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1 <span class="sr-only">(current)</span></a>
            </li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Next">
                  <i class="fa fa-angle-double-right" aria-hidden="true"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
