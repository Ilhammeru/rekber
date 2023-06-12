<x-mail::message>
@lang('global.line_need_confirm_payment')

<x-mail::table>
    | @lang('global.name')                        | @lang('global.transaction') ID     | @lang('global.amount')               |
    | ----------------------------------------- | :--------------------------------: | -----------------------------------: |
    | {{$data->user->email}}                      | {{$data->uuid}}    | {{number_format($data->amount)}}
</x-mail::table>

<x-mail::button :url="$url" color="primary">
    @lang('global.click_to_see_detail')
</x-mail::button>


    {{ config('app.name') }}
</x-mail::message>

