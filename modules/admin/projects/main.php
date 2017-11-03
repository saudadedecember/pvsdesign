<?php
$sth = DB::selectProject($dbh, Core::DISPLAY_PROJECTS);

$pages = ceil(DB::countRows($dbh, 'projects') / Core::DISPLAY_PROJECTS);
