<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>HOME!!!!</title>

  <!-- Bootstrap core CSS -->
  <link href="<?php out::url('css/bootstrap.min.css'); ?>" rel="stylesheet">
  <style type="text/css">
    html,
    body {
      height: 100%
    }

    .loading {
      background-color: lightgrey;
    }

    .closed {
      background-color: lightyellow;
    }

    .dimmed {
      opacity: 0.6;
    }

    .inprogress {
      background-color: lightcyan;
    }

    .instance {
      transition: background-color 500ms linear;
    }

    input[type="radio"] {
      opacity: 0;
    }

    input[type="radio"]:checked+label {
      font-weight: bold;
      border-bottom: 2px solid black;
    }

    input[type="radio"]:focus+label {
      border-bottom: 2px solid var(--bs-primary);
    }
  </style>
</head>

<body <?php out::bodyData() ?>>
  <main role="main" class="container">

    <div class="starter-template">
      <div class="container">
        <div class="row h-100">
          <div class="col-sm">
            <h2 data-tracker="refresh">Local instances <a href="?refresh=true">[Reload]</a></h2>
            <?php
              $template->render('filters');
            ?>
            <ul class="list-group" id="instanceslist">
              <?php
              foreach ($instances as $info) {
                $icon = instance_icon_url($info);
                $alert = ($info->rebase) ? 'text-danger' : 'text-muted';
                ?>
                <li
                  class="list-group-item instance d-flex flex-column"
                  data-mdl="<?php echo $info->mdl; ?>"
                  data-type="<?php echo $info->type; ?>"
                >
                  <div class="d-flex align-items-center">
                    <img class="me-2" src="<?php echo $icon; ?>" height="25">
                    <a href="<?php echo $CFG->moodleswwwroot . '/' . $info->name; ?>">
                      <?php echo $info->name; ?>
                    </a>
                    <small
                      class="dimmed d-none ms-2 me-auto text-secondary fst-italic"
                      data-tracker="branch"
                    ><?php echo $info->branch; ?></small>
                    <small class="<?php echo $alert; ?> ms-auto"><?php echo $info->version; ?></small>
                    <?php
                    if (!empty($info->idelink)) {
                    ?>
                    <a class="ms-2" href="<?php echo $info->idelink; ?>">
                      <img src="<?php echo $CFG->wwwpix . '/vscode.png'; ?>" height="18">
                    </a>
                    <?php
                    }
                    ?>
                    <span class="ms-2" title="Unknown" data-tracker="status">
                      <a href="#" data-tracker="url">
                        <img src="<?php echo $CFG->wwwpix . '/help.gif'; ?>" alt="ISSUE" data-tracker="icon">
                      </a>
                    </span>
                  </div>
                  <div>
                    <small data-tracker="title"><?php echo $info->title; ?></small>
                  </div>
                </li>
                <?php
              }
              ?>
            </ul>
          </div>
          <div class="col-sm">
            <?php foreach ($CFG->extras as $title => $links) { ?>
              <h2><?php echo $title; ?></h2>
              <ul class="list-group">
                <?php foreach ($links as $link) { ?>
                  <li class="list-group-item">
                    <a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a>
                  </li>
                <?php } ?>
              </ul>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

  </main><!-- /.container -->

  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="<?php out::url('js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?php out::url('js/tracker.js'); ?>"></script>
</body>

</html>
