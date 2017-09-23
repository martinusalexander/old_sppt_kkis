<body>
    
    <p>Hi {{ $name }},</p>
    <p>Selamat datang dalam Sistem Pengelolaan Pengumuman Terpadu KKIS.</p>
    <p>Segera aktifkan akun Anda dengan mengklik tautan <a href="{{ URL::to('/') }}/activateaccount/{{ $token }}">ini</a>.</p>
    <p>Salam,
    <br>
    Humas Intern KKIS</p>
    
</body>