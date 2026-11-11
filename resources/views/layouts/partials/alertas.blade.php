{{-- SweetAlert Flash Alerts --}}
@if (session('alert'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '{{ session('alert.icon', 'info') }}',
            title: '{!! addslashes(session('alert.title', '')) !!}',
            text: '{!! addslashes(session('alert.text', '')) !!}',
            confirmButtonColor: '#0284c7',
            confirmButtonText: '<i class="fas fa-check mr-2"></i>Entendido',
            customClass: { popup: 'rounded-[2rem] font-outfit' }
        });
    });
</script>
@endif

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Operación Exitosa',
            text: '{!! addslashes(session('success')) !!}',
            confirmButtonColor: '#0284c7',
            confirmButtonText: 'Aceptar',
            customClass: { popup: 'rounded-[2rem] font-outfit' }
        });
    });
</script>
@endif

@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Atención',
            text: '{!! addslashes(session('error')) !!}',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Entendido',
            customClass: { popup: 'rounded-[2rem] font-outfit' }
        });
    });
</script>
@endif

