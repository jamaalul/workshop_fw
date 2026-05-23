<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian — {{ config('app.name') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body { background-color: #f2edf3; }
        .full-page-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow w-100">
                    <div class="col-lg-5 mx-auto">
                        <div class="auth-form-light text-left p-5 shadow-sm rounded">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-primary"><i class="mdi mdi-ticket-account me-2"></i>Daftar Antrian</h3>
                                <h6 class="font-weight-light">Masukkan nama Anda untuk mendapatkan nomor antrian</h6>
                            </div>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                                    <i class="mdi mdi-alert-circle-outline me-2"></i>
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('antrian.guest.store') }}" id="guestForm" class="pt-3">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="name" class="fw-semibold text-secondary">Nama Lengkap</label>
                                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda..." autocomplete="off" autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-3 d-grid">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" id="submitBtn">
                                        <i class="mdi mdi-ticket-confirmation me-2"></i>Ambil Nomor Antrian
                                    </button>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    <a href="{{ route('antrian.board') }}" class="text-primary text-decoration-none" target="_blank">
                                        <i class="mdi mdi-monitor me-1"></i>Lihat Papan Antrian
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Open ticket in new tab when form submitted
        document.getElementById('guestForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const name = document.getElementById('name').value.trim();
            if (!name) return;

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Mendaftarkan...';

            // Submit via hidden form targeting a new tab
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('antrian.guest.store') }}';
            form.target = '_blank';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = name;

            form.appendChild(csrfInput);
            form.appendChild(nameInput);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            // Reset original form
            setTimeout(() => {
                document.getElementById('name').value = '';
                btn.disabled = false;
                btn.innerHTML = '<i class="mdi mdi-ticket-confirmation me-2"></i>Ambil Nomor Antrian';
            }, 1500);
        });
    </script>
</body>
</html>
