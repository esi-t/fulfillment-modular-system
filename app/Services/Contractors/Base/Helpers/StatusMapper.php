<?php

namespace App\Services\Contractors\Base\Helpers;

use App\Services\Contractors\Aldy\Enums\StatusesEnum as AldyStatus;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Digi\Enums\StatusesEnum as DigiStatus;
use App\Services\Contractors\Snap\Enums\StatusesEnum as SnapStatus;

class StatusMapper
{
    // TODO : refactor this
    public static function map(int|string $status, int $channelId): int
    {
        $self = (new static());

        return match ($channelId) {
            ChannelsEnum::Snap->value => $self->snap($status),
            ChannelsEnum::Aldy->value => $self->aldy($status),
            ChannelsEnum::Digi->value => $self->digi($status),
            ChannelsEnum::FoodSnap->value => $self->foodSnap($status),
        };
    }

    private function snap(int $status): int
    {
        return $status;
    }

    private function aldy(int $status): int
    {
        return match ($status) {
            AldyStatus::NewOrder->value => SnapStatus::NewOrder->value,
            AldyStatus::Acknowledge->value => SnapStatus::Acknowledge->value,
            AldyStatus::Pick->value => SnapStatus::Acknowledge->value,
            AldyStatus::Accept->value => SnapStatus::AcceptedOrder->value,
            AldyStatus::Nfc->value => SnapStatus::NeedForCallByClient->value,
            default => $status
        };
    }

    private function digi(string $status): int
    {
        return match ($status) {
            DigiStatus::New->value => SnapStatus::NewOrder->value,
            DigiStatus::Acknowledge->value => SnapStatus::Acknowledge->value,
            DigiStatus::Pick->value => SnapStatus::Acknowledge->value,
            DigiStatus::Accept->value => SnapStatus::AcceptedOrder->value,
            DigiStatus::Edit->value => SnapStatus::NeedForCall->value,
            DigiStatus::Reject->value => SnapStatus::CanceledOrder->value,
            DigiStatus::Cancel->value => SnapStatus::CanceledOrder->value,
            DigiStatus::Done->value => SnapStatus::Acknowledge->value,
            default => $status
        };
    }

    private function foodSnap(string $status): int
    {
        return (int)$status;
    }
}
