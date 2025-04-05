<div>
    <form action="{{ "/" . $uri_tree[0] . "/authenticate" }}" method="GET">
        <label for="">ログインID:</label>
        <input type="text" name="loginId">
        @if ($errors->first('loginId'))
            {{ $errors->first('loginId') }}
        @endif
        <br>

        <label for="">パスワード:</label>
        <input type="password" name="password">
        @if ($errors->first('password'))
            {{ $errors->first('password') }}
        @endif
        <br>

        <button>ログイン</button>
    </form>
</div>