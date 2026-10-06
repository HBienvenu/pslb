@php
    $swalToast = [
        'success' => session('success'),
        'error' => session('error'),
        'info' => session('info'),
        'warning' => session('message'),
    ];
    $icon = null;
    $message = null;
    foreach ($swalToast as $type => $val) {
        if ($val) {
            $icon = $type;
            $message = $val;
            break;
        }
    }
@endphp

@if ($icon || session('warning'))
    <script>
        (function() {
            function ready(fn) {
                if (window.Swal) {
                    fn();
                    return;
                }
                var attempts = 0;
                var timer = setInterval(function() {
                    if (window.Swal || ++attempts > 40) {
                        clearInterval(timer);
                        if (window.Swal) fn();
                    }
                }, 50);
            }

            ready(function() {
                @if (session('warning'))
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attention',
                        text: '{{ addslashes(session('warning')) }}',
                        showCloseButton: true, // Ajoute une croix
                        closeButtonHtml: '&times;', // Personnaliser la croix
                    });
                @else
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true, // ✅ Ajoute la croix
                        closeButtonHtml: '&times;', // Personnaliser la croix (optionnel)
                        timer: 6000,
                        timerProgressBar: true,
                        didOpen: function(t) {
                            t.addEventListener('mouseenter', Swal.stopTimer);
                            t.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    }).fire({
                        icon: '{{ $icon }}',
                        title: '{{ addslashes($message) }}'
                    });
                @endif
            });
        })();
    </script>
@endif
