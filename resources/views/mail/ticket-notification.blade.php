<x-mail::message>
# Update Ticket Notification

Ticket dengan nomor {{ $ticket->id }} telah diperbarui.

**Detail Ticket:**

* Judul: {{ $ticket->title }}
* Tanggal: {{ $reply->created_at->isoFormat('ddd, DD MMM YYYY HH:mm') }} WIB
* Pesan: 
{!! $reply->messages !!}

Terima kasih telah menggunakan sistem tiket kami.

Best,
{{ config('app.name') }}
</x-mail::message>
