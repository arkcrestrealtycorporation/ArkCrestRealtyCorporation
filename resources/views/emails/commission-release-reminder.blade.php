<x-mail::message>
# Commission Release Reminder

Hello,

The following commission(s) are scheduled for release **tomorrow, {{ $releaseDate }}**:

<x-mail::table>
| Agent | Project | Client | Commission |
|:------|:--------|:-------|:----------:|
@foreach($releases as $r)
| {{ $r->agent_name ?? '—' }} | {{ $r->project_name ?? '—' }} | {{ $r->client_name ?? '—' }} | ₱{{ number_format($r->commission, 2) }} |
@endforeach
</x-mail::table>

Please ensure all necessary preparations are in order before the release date.

<x-mail::button :url="config('app.url') . '/commission-monitoring'">
View Commission Monitoring
</x-mail::button>

Thanks,
{{ config('app.name') }}
</x-mail::message>
