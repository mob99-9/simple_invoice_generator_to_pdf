<?php

// Force the working directory back to the repository root root
chdir(__DIR__ . '/..');

// Explicitly register Laravel's core public handler
require __DIR__ . '/../public/index.php';