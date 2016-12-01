<?php

include "settings/init.php";
include 'settings/page.class.php';


$page->title = "Users of ". $set_class->site_name;

$nav->setActive("userslist");


$content = '';

if(!isset($_GET['page']))
  $page_number = 1;


$sort_name = array("id", "name");

if(!isset($_GET['sort']) || !in_array($_GET['sort'], array(0,1)))
  $sort = 0;
else
  $sort = (int)$_GET['sort'];

if(!isset($_GET['sort_type']) || !in_array($_GET['sort_type'], array(0,1)))
  $sort_type = 0;
else
  $sort_type = (int)$_GET['sort_type'];


if($sort == 1) {
  $order_by = "`username` ". (!$sort_type ? "ASC" : "DESC");
} else {
  $order_by = "`userid` ". (!$sort_type ? "ASC" : "DESC");
}

$show_sort_options = '';
foreach ($sort_name as $a => $o) {
  if($a != $sort)
    $show_sort_options .= "<li><a href='?sort=$a'>Sort by $o</a></li>";
}

$where = '';

if(isset($_GET['q']))
  $where = $db->parse("WHERE `username` LIKE ?s", '%'.$_GET['q'].'%');


if($total_results = $db->getRow("SELECT COUNT(*) as count FROM `".OneLook_PREFIX."users` ?p", $where)->count) {

    if(!isset($page_number))
      $page_number = (int)$_GET['page'] <= 0 ? 1 : (int)$_GET['page']; // grab the page number

    $perpage = 10;

    if($page_number > ceil($total_results/$perpage))
      $page_number = ceil($total_results/$perpage);


    $start = ($page_number - 1) * $perpage;

    $data = $db->getAll("SELECT * FROM `".OneLook_PREFIX."users` ?p ORDER BY ?p LIMIT ?i,?i", $where, $order_by, $start, $perpage);


    $pagination = new page($total_results, $page_number, $perpage);



    foreach($data as $u) {
    	$content .= "<li class='span5 clearfix'>
      <div class='thumbnail clearfix'>
    	<a href='$set_class->url/profile.php?u=$u->userid'><img src='".$the_user->getAvatar($u->userid)."' width='80' alt='".$html_options->html($u->username)."' class='pull-left clearfix' style='margin-right:10px'>
        <div class='caption' class='pull-left'>
          <h4>
    	      <a href='$set_class->url/profile.php?u=$u->userid'>".$the_user->showName($u->userid)."</a>
          </h4>
          <small><b>Last seen: </b> ".$html_options->tsince($u->lastactive)."</small>

          </div>
        </div>
      </li>";
    }
} else
  $page->error = "No results were found !";




include 'header.php';



echo "
<div style='margin-top: 150px; margin-bottom: 150px;' class='container'>

  <h3 class='pull-left'>Users on ".$set_class->site_name."</h3>

  <form class='form-search' action='?'>
    <div class='input-append pull-right'>
      <input class='span2 search-query' name='q' type='text' ".( isset($_GET['q']) ? "value='".$html_options->html($_GET['q'])."'" : "" )." placeholder='Search...'/>
      <button type='submit' class='btn'><i class='icon-search'></i></button>

      ".$html_options->queryString("hidden", array("q","page"))."
    </div>
  </form>
  <div class='clearfix'></div>

  <div class='btn-group pull-right'>
    <a class='btn btn' href='?sort=$sort&sort_type=".(!$sort_type ? 1 : 0)."'><i class='icon-chevron-".(!$sort_type ? 'up' : 'down')."'></i> Sort by ".$sort_name[$sort]."</a>
    <a class='btn btn dropdown-toggle' data-toggle='dropdown' href='#'><span class='caret'></span></a>
    <ul class='dropdown-menu'>
      $show_sort_options
    </ul>
  </div>
  <div class='clearfix'></div>";

  if(isset($data))
    echo "<small>Showing ".($start+1)."-".($start+count($data))." out of ".$total_results."</small><hr>";
  else
    echo "<hr>";


if(isset($page->error))
  $html_options->error($page->error);
else if(isset($page->success))
  $html_options->success($page->success);


echo "
  <ul class='thumbnails'>
		$content
	</ul>
".(isset($pagination) ? $pagination->pages : "" )."
</div>";


include 'footer.php';
