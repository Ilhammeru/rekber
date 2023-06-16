<x-mail::message>
# @lang('global.deposit_decline')

@lang('global.user_deposit_decline_notification', [
    'amount' => number_format($data->amount),
    'reason' => $data->depositManual->reason,
])

<br>
<br>

{{ config('app.name') }}
</x-mail::message>
