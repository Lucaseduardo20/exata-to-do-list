<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';

    case DONE = 'done';

    case IN_PROGRESS = 'in-progress';

    public function formatted(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::DONE => 'Concluída',
            self::IN_PROGRESS => 'Em andamento',
        };
    }
}
