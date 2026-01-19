@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
<div class="auth-wrap">
  <div class="auth-card" style="text-align:center;">

    <p class="auth-verify-message">
      登録していただいたメールアドレスに認証メールを送付しました。<br>
      メール認証を完了してください。
    </p>

    {{-- 認証メール再送が成功したとき --}}
    @if (session('status') == 'verification-link-sent')
      <p style="color:green; font-size:13px; margin-top:10px;">
        認証メールを再送しました。
      </p>
    @endif

    <div style="margin-top:24px;">
      {{-- 「認証はこちらから」＝メールを開け、ではなく再送 or 認証導線にしたい場合 --}}
      {{-- 通常は“メール内リンクを踏む”のでボタンは「認証メールを再送する」にするのが自然 --}}
      <form method="POST" action="{{ route('verification.send') }}" style="display:inline;">
        @csrf
        <button type="submit"
          style="
            padding:10px 18px;
            border:1px solid #999;
            background:#e6e6e6;
            border-radius:4px;
            cursor:pointer;
            font-weight:700;
          ">
          認証はこちらから
        </button>
      </form>
    </div>

    <div style="margin-top:18px;">
      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
          style="
            border:none;
            background:none;
            color:#1e6bd6;
            cursor:pointer;
            font-size:14px;
          ">
          認証メールを再送する
        </button>
      </form>
    </div>

  </div>
</div>
@endsection
