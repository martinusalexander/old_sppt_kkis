<body>
    
    <p>Hi {{ $name }},</p>
    <p>Kami telah menerima permintaan dari {{ $creator_name }} untuk {{ $action }} pengumuman melalui media {{ $media_name }} pada {{ $date_time }}.</p>
    <p>Berikut adalah deskripsi dari pengumuman tersebut.</p>
    <p>
        <pre><b>{{ $title }}</b></pre>
        <pre>{{ $description }}</pre>
    </p>
    <p>Catatan: Harap masukkan attachment sebagai gambar pendukung pengumuman (jika ada).</p>
    <p>Salam,
    <br>
    Humas Intern KKIS</p>
    
</body>