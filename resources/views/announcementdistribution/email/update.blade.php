<body>
    
    <p>Hi {{ $name }},</p>
    <p>Kami telah menerima permintaan dari {{ $creator_name }} untuk {{ $action }} pengumuman melalui media online pada {{ $date_time }}.</p>
    <p>Berikut adalah detil dari pengumuman tersebut.</p>
    <p>
        <ul>
            @foreach ($content as $media => $description)
            <li>
                <p>{{ $media }}</p>
                <pre><b>{{ $title }}</b></pre>
                <pre>{{ $description }}</pre>
            </li>
            @endforeach
        </ul>
    </p>
    <p>Catatan: Harap masukkan attachment sebagai gambar pendukung pengumuman (jika ada).</p>
    <p>Salam,
    <br>
    Humas Intern KKIS</p>
    
</body>