<?php

declare(strict_types = 1);

namespace Larium\Model;

interface DescriptorInterface
{
    public function getReferenceId(): string;

    public function getDescription(): string;
}
