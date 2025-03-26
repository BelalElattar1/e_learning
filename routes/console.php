<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('expire:subscription')->dailyAt('17:35');
