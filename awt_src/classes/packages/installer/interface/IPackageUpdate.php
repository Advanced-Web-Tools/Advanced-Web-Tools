<?php

namespace packages\installer\interface;

interface IPackageUpdate
{
    public function update(int $packageId, string $packageName): bool;
}