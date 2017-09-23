<body>
    
    <p>Hi {{ $name }},</p>
    <p>Kami telah menerima permintaan untuk mengatur ulang (reset) password Anda.</p>
    <p>Segera atur ulang password Anda dengan mengklik tautan <a href="{{ URL::to('/') }}/resetpassword/{{ $token }}">ini</a>.</p>
    <p>Jika bukan Anda yang melakukan permintaan ini, mohon hubungan administrator melalui email <a href="mailto:humas.intern@kkis.org?Subject=Permintaan%20Atur%20Ulang%20Password" target="_top"></a></p>
    <p>Salam,
    <br>
    Humas Intern KKIS</p>
    
</body>