<?php

namespace Deployer;

desc('Reload swoole');
task('artisan:swoole:reload', artisan('swoole:http reload'));
