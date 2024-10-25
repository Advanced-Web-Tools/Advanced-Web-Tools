<?php

namespace packages\runtime;
use packages\enums\EPackageStatus;
use packages\Package;

class Runtime extends Package {
    public string $installedByUsername;
    public function __construct() {
        parent::__construct();
    }
}