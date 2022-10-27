<table
    cellpadding="0"
    cellspacing="0"
    width="100%"
    style="border: none; margin: 30px 0;"
>
    <tr>
        <td style="padding: 38px 20px 20px 20px; text-align: center;">
            {!! html_entity_decode(trans('gerencianet::general.email.pix_image')) !!}
        </td>
    </tr>
    <tr>
        <td style="padding: 20px;" align="center">
            <img src="{{ $email['embed_image'] }}" alt="Pix QR Code" style="height: auto;max-width: 250px;">
        </td>
    </tr>
    <tr>
        <td style="padding: 20px; text-align: center">
            {!! html_entity_decode(trans('gerencianet::general.email.pix_code')) !!}
        </td>
    </tr>
    <tr>
        <td style="padding:0 50px;">
            <p style="border: 1px solid #e7e7e7; line-height: normal; word-break: break-all; padding: 16px;">{{ $email['pix_code'] }}</p>
        </td>
    </tr>
</table>
