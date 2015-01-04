<nav class="navbar navbar-default navbar-static-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#videos-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="">Videos</a>
    </div>
    <div class="collapse navbar-collapse" id="videos-navbar-collapse">
      <ul class="nav navbar-nav">
<?php
  $links = array(
    array('Home', 'Home', ''),
    array('Videos', 'All Videos', 'videos/')
  );
?>
<?php
  foreach($links as $link):
    list($link_title, $link_text, $link_href) = $link;
    $selected = $link_title === $title;
?>
        <li<?php if($selected) { ?> class="active"<?php } ?>>
          <a href="<?= $link_href ?>"><?=
            $link_text
          ?><?php
            if($selected) { ?><span class="sr-only"> (current)</span><?php }
          ?></a>
        </li>
<?php
  endforeach;
?>
      </ul>
      <form class="navbar-form navbar-right" role="search" method="GET" action="videos/search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Type any tag" name="query" />
        </div>
        <button type="submit" class="btn btn-default">Search</button>
      </form>
    </div>
  </div>
</nav>
