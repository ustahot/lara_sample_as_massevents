<?php

namespace App\Models\Massevent\Placeholder;

class MassSmsPlaceholder
{
    public function __construct
    (public readonly string $code, public readonly string $description)
    {
    }

    /**
     * @return self[]
     */
    public static function all(): array
    {
        $all = [];
        $all[] = new self(code: 'ticket_url', description: 'url на QR-код билета участника');
        $all[] = new self(code: 'ticket_plan', description: 'забронировано билетов для участника');
        $all[] = new self(code: 'ticket_word', description: 'Слово "билет(ы)" в родительном падеже, в зависимости от количества');

        return $all;
    }


}
