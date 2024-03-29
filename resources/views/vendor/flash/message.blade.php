@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div id="flashData" class="alert-flash-container alert-flash
                    alert-{{ $message['level'] }}
        {{ $message['important'] ? 'alert-important' : '' }}"
             role="alert"
        >

            <button type="button"
                    class="close"
                    id="alert-flash-dissmiss"
                    data-dismiss="alert"
                    aria-hidden="true"
            >&times;
            </button>

            {!! $message['message'] !!}
        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
