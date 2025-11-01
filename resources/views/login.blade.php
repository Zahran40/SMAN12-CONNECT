<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:#e6f0fa;">
    <div style="background:#fff; border-radius:32px; box-shadow:0 6px 16px 0 rgba(0,0,0,0.10); display:flex; max-width:980px; width:90%;">
        <div style="flex:1; padding:48px 40px 48px 40px; border-radius:32px 0 0 32px;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                <img src="/images/logo_sman12.png" alt="Logo" style="height:36px;">
                <div style="background:#4eaaff; color:white; font-weight:500; border-radius:24px; padding:4px 18px; font-size:16px;">
                    SMA NEGERI 12 MEDAN
                </div>
            </div>
            <h2 style="color:#2a5db0; font-weight:700; font-size:32px; margin-bottom:26px;">Masuk</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div style="margin-bottom:20px;">
                    <label for="login" style="font-size:15px;">NIP/NIS/Email Terkait</label>
                    <input id="login" type="text" name="login" class="form-control" placeholder="89823xxxxx" required autofocus 
                        style="width:100%; padding:12px 14px; border:1.5px solid #bcd6f6; border-radius:10px; font-size:16px; margin-top:5px;">
                </div>
                <div style="margin-bottom:28px;">
                    <label for="password" style="font-size:15px;">Kata Sandi</label>
                    <input id="password" type="password" name="password" class="form-control" required 
                        style="width:100%; padding:12px 14px; border:1.5px solid #bcd6f6; border-radius:10px; font-size:16px; margin-top:5px;">
                </div>
                <button type="submit" style="width:100%; background:#4eaaff; color:#fff; border:none; border-radius:24px; padding:13px; font-size:17px; font-weight:500; box-shadow:0 2px 8px 0 rgba(78,170,255,0.16); cursor:pointer;">
                    Masuk
                </button>
            </form>
        </div>
        <div style="flex:1; background:linear-gradient(135deg,#46a8fe 0%,#5db6fd 100%); border-radius:0 32px 32px 0; display:flex; align-items:center; justify-content:center; position:relative;">
            <div style="color:#fff; text-align:center; width:100%;">
                <span style="font-size:38px; font-weight:700; letter-spacing:1px;">SELAMAT<br>DATANG</span>
            </div>
        </div>
    </div>
    <div style="position:absolute; top:32px; left:32px;">
        <a href="{{ url()->previous() }}" style="display:inline-flex; align-items:center; background:#fff; border-radius:16px; box-shadow:0 2px 8px 0 rgba(44, 120, 255, 0.09); padding:7px 20px 7px 13px; color:#2a5db0; font-weight:500; text-decoration:none; font-size:18px;">
            <svg width="24" height="24" style="margin-right:8px;" fill="none" stroke="#2a5db0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali
        </a>
    </div>
</div>
