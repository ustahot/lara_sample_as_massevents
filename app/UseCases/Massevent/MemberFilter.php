<?php

namespace App\UseCases\Massevent;

use App\Http\Requests\Massevent\Api\Member\FilterRequest;
use App\Models\Massevent\Member;
use App\UseCases\ModelFilterAbstract;
use App\UseCases\ModelFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
//use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;


class MemberFilter extends ModelFilterAbstract implements ModelFilterInterface
{

    public static function make(array $validated)
    {
        return new self(Member::class, $validated);
    }

//    public function getFilteredCollection(): \Illuminate\Database\Query\Builder
    public function getFilteredCollection(): \Illuminate\Database\Eloquent\Builder
    {

        // Обычные атрибуты (скаляры)
        $possibleEqualAttributes = [
            'massevent_id',
            'category_id',
        ];

        $query = $this->prepareQueryByAndArguments($possibleEqualAttributes);

        // Перечень статусов
        if (isset($this->validatedRequest['status_list'])) {
            $statuses = explode(',', $this->validatedRequest['status_list']);

            if (in_array('null', $statuses)) {

                $query = $query->where(function (Builder $query) use ($statuses) {
                    $query->whereIn('status',$statuses)
                        ->orWhereNull('status');
                });

            } else {
                $query = $query->whereIn('status',$statuses);
            }
        }

        // Фрагмент имени и/или телефона и/или email-а
        if (isset($this->validatedRequest['find_fragment'])) {
            $fragment = '%' . trim($this->validatedRequest['find_fragment']) . '%';

            $query->where(function (Builder $query) use ($fragment) {
                $query->whereLike('name_for_massevent', $fragment)
                    ->orWhereLike('phone_for_massevent', $fragment)
                    ->orWhereLike('email_for_massevent', $fragment);
            });
        }

        return $query;
    }

}
