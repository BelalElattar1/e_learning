<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('expire:subscription')->daily();