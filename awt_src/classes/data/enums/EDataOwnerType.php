<?php

namespace data\enums;

/**
 * Enum EDataOwnerType
 * Defines the possible owner types for data.
 *
 * - User: Represents data owned by a user.
 * - System: Represents system-owned data.
 * - Package: Represents data owned by a package.
 */
enum EDataOwnerType
{
    case User;
    case System;
    case Package;
}
