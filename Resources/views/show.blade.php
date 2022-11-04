<div>
    @if (!empty($setting['description']))
    <div>
        <div class="well well-sm">
            {{ $setting['description'] }}
        </div>
    </div>
    <br />
    @endif
    <div>
        <div class="well well-sm">
            {!! trans('gerencianet::general.portal.pix_info') !!}
            <br />
            <br />
            <div class="flex flex-col items-center">
                <img
                    src="{{ $payment['imagemQrcode'] }}"
                    width="250px"
                />
                <img
                    src="{{ asset('modules/Gerencianet/Resources/assets/img/pix-bcb.svg') }}"
                    alt="pix-bcb.svg"
                    width="240px"
                    class="my-10"
                />
            </div>
            <input
                id="qrcode_text"
                type="text"
                value="{{ $payment['qrcode'] }}"
                onclick="this.setSelectionRange(0, this.value.length); document.execCommand('copy'); this.nextElementSibling.setAttribute('class', 'flex justify-center');"
                class="w-full"
            />
            <span class="hidden">
                <strong>{{ trans('gerencianet::general.portal.copied') }}</strong>
            </span>
        </div>
    </div>
</div>
